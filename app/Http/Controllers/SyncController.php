<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Service;
use App\Models\Sales;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Models\MpesaTransaction;
use Illuminate\Support\Str;


class SyncController extends Controller
{
    public function push(Request $request)
    {
        $user = $request->user();
        $ops = $request->input('operations', []);
        $results = [];

        foreach ($ops as $op) {
            $model = $op['model'] ?? null;
            $action = $op['action'] ?? null;
            $tempId = $op['temp_id'] ?? null;
            $data = $op['data'] ?? [];

            // Minimal validation
            if (!$model || !$action) {
                $results[] = ['temp_id' => $tempId, 'model' => $model, 'success' => false, 'error' => 'invalid_operation'];
                continue;
            }

            try {
                DB::beginTransaction();
                $serverId = null;

                switch ($model) {
                    case 'sale':
                        if ($action === 'create') {
                            // Create sale (simplified). Expand to create Sales rows, link to cart etc.
                            $sale = new Sales();
                            $sale->cart_id = $data['cart_id'] ?? null;
                            $sale->product_name = $data['product_name'] ?? ($data['items'][0]['name'] ?? null);
                            $sale->description = $data['description'] ?? null;
                            $sale->price = $data['price'] ?? $data['total'] ?? 0;
                            $sale->active_price = $data['active_price'] ?? null;
                            $sale->discount_price = $data['discount_price'] ?? null;
                            $sale->quantity = $data['quantity'] ?? 1;
                            $sale->total = $data['total'] ?? 0;
                            $sale->payment_method = $data['payment_method'] ?? 'cash';
                            $sale->business_id = $user->business_id;
                            $sale->save();

                            // optional: create sale lines for every item (if you have a SalesLine model)
                            $serverId = $sale->id;
                        }
                        break;

                    case 'client':
                        if ($action === 'create') {
                            $client = Client::create(array_merge($data, [
                                'business_id' => $user->business_id,
                                'created_by' => $user->id,
                            ]));
                            $serverId = $client->id;
                        }
                        break;

                    case 'service':
                        if ($action === 'create') {
                            $service = Service::create(array_merge($data, [
                                'business_id' => $user->business_id,
                                'created_by' => $user->id,
                            ]));
                            $serverId = $service->id;
                        }
                        break;

                    case 'mpesa_transaction':
                        if ($action === 'create') {
                            $mp = MpesaTransaction::create(array_merge($data, [
                                'business_id' => $user->business_id,
                            ]));
                            $serverId = $mp->id;
                        }
                        break;

                    default:
                        throw new \Exception('unknown_model');
                }

                DB::commit();
                $results[] = ['temp_id' => $tempId, 'model' => $model, 'success' => true, 'server_id' => $serverId];

            } catch (\Throwable $e) {
                DB::rollBack();
                // Log error
                \Log::error('Sync push error: ' . $e->getMessage(), ['op' => $op]);
                $results[] = ['temp_id' => $tempId, 'model' => $model, 'success' => false, 'error' => $e->getMessage()];
            }
        }

        return response()->json(['results' => $results]);
    }

    // Optional: server -> client pull endpoint to fetch changes newer than client timestamp
    public function pull(Request $request)
    {
        $since = $request->query('since'); // ISO timestamp
        $businessId = $request->user()->business_id;

        // Example: return new sales, clients, services since $since
        $sales = Sales::where('business_id', $businessId)
                      ->when($since, fn($q) => $q->where('created_at', '>', $since))
                      ->get();

        $clients = Client::where('business_id', $businessId)
                      ->when($since, fn($q) => $q->where('updated_at', '>', $since))
                      ->get();

        return response()->json([
            'sales' => $sales,
            'clients' => $clients,
            // 'services' => ...
        ]);
    }

   public function sales(Request $request) {
    foreach ($request->sales as $sale) {
        Sales::create($sale);
    }
    return ['status' => 'ok'];
}

public function updates(Request $request) {
    foreach ($request->updates as $update) {
        $id = collect($update)->firstWhere('name', 'id')['value'] ?? null;
        if ($id) {
            $data = collect($update)->mapWithKeys(fn($f) => [$f['name'] => $f['value']]);
            Product::where('id', $id)->update($data->toArray());
        }
    }
    return ['status' => 'ok'];
}

public function receive(Request $request)
    {
        $actions = $request->input('actions', []);

        if (!is_array($actions) || empty($actions)) {
            return response()->json(['ok' => false, 'message' => 'No actions to process'], 400);
        }

        $results = [];

        foreach ($actions as $action) {
            try {
                $url = $action['url'] ?? null;
                $method = strtoupper($action['method'] ?? 'POST');
                $data = $action['data'] ?? [];

                if (!$url) {
                    $results[] = ['ok' => false, 'error' => 'Missing URL'];
                    continue;
                }

                // Simulate a sub-request to the original route
                $subRequest = Request::create($url, $method, $data);

                // Copy over user auth context
                $subRequest->setUserResolver(function () use ($request) {
                    return $request->user();
                });

                // Dispatch internally to Laravel
                $response = Route::dispatch($subRequest);

                $decoded = json_decode($response->getContent(), true);
                $results[] = [
                    'ok' => $response->isOk(),
                    'status' => $response->status(),
                    'response' => $decoded ?? $response->getContent(),
                ];
            } catch (\Throwable $e) {
                Log::error("Sync action failed", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $results[] = ['ok' => false, 'error' => $e->getMessage()];
            }
        }

        return response()->json(['ok' => true, 'results' => $results]);
    }
    private function processCheckout(array $data)
    {
        // Here, directly call your existing checkout logic
        // Example: app()->call('App\Http\Controllers\POSController@checkout', [$data]);

        // For safety, ensure it's idempotent and handles stock/payment correctly
    }

    private function processAddByBarcode(array $data)
    {
        // Call your logic for adding item by barcode
        // Example: app()->call('App\Http\Controllers\POSController@addCartByBarcode', [$data]);
    }

}


