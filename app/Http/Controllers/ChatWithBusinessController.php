<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadedSale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use \Illuminate\Validation\ValidationException;
use App\Models\Sales;
use App\Models\Product;
use App\Models\Debt;
use App\Models\Customer;
use App\Models\ProductImportLog;
use App\Models\Category;
use App\Models\Expense;
use App\Models\DebtItem;

class ChatWithBusinessController extends Controller
{
    public function showChat()
    {
        return view('admin.chat.business');
    }

    public function uploadSales(Request $request)
    {
        $request->validate([
            'sales_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('sales_file')->store('sales_uploads');

        UploadedSale::create([
            'business_id' => Auth::user()->business_id,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Sales data uploaded!');
    }

  public function askBusinessQuestion(Request $request)
{
    try {
        $request->validate(['question' => 'required|string']);

        $businessId = Auth::user()->business_id;

        $products = Product::where('business_id', $businessId)->get()->toArray();
        $sales = Sales::where('business_id', $businessId)->get()->toArray();
        $customers = Customer::where('business_id', $businessId)->get()->toArray();
        $debts = Debt::where('business_id', $businessId)->get()->toArray();
        $imported_by = ProductImportLog::where('business_id', $businessId)->get()->toArray();
        $category = Category::where('business_id',$businessId)->get()->toArray();
        $expense = Expense::where('business_id',$businessId)->get()->toArray();
        $debtitem = DebtItem::where('business_id',$businessId)->get()->toArray();

        $businessData = [
            'products' => $products,
            'sales' => $sales,
            'customers' => $customers,
            'debts' => $debts,
            'product_import_logs' => $imported_by,
            'categories' => $category,
            'expenses' => $expense,
            'debt_items' => $debtitem
        ];

        $context = json_encode($businessData);

        $prompt = <<<EOT
You are a smart business analyst. You have access to the business database in JSON format.

BUSINESS DATA:
$context

QUESTION: {$request->question}

Answer with clear business insights (not raw JSON). Be concise but useful. Add financial or operational reasoning if possible.
EOT;

        $response = \Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('OPENROUTER_API_URL') . '/chat/completions', [
            'model' => env('OPENROUTER_MODEL'),
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful business analyst.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'AI API failed', 'details' => $response->body()], 500);
        }

        $data = $response->json();
        $reply = $data['choices'][0]['message']['content'] ?? 'No response.';

        return response()->json(['answer' => $reply]);

    } catch (ValidationException $e) {
        return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Unexpected error', 'details' => $e->getMessage()], 500);
    }
}


}
