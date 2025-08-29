<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadedSale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Dedt;
use App\Models\Customer;

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
    $request->validate(['question' => 'required|string']);
    $businessId = Auth::user()->business_id;

    // ✅ Check if a file was uploaded (latest)
    $upload = UploadedSale::where('business_id', $businessId)->latest()->first();

    if ($upload && Storage::exists($upload->file_path)) {
        // Read uploaded CSV
        $csvContent = Storage::get($upload->file_path);

        $context = "SALES DATA (CSV format):\n" . $csvContent;
    } else {
        // No file uploaded → fallback to DB data
        $products = Product::where('business_id', $businessId)->get()->toArray();
        $sales = Sales::where('business_id', $businessId)->get()->toArray();
        $customers = Customer::where('business_id', $businessId)->get()->toArray();
        $debts = Debt::where('business_id', $businessId)->get()->toArray();

        $context = "BUSINESS DATA (JSON):\n" . json_encode([
            'products' => $products,
            'sales' => $sales,
            'customers' => $customers,
            'debts' => $debts,
        ]);
    }

    $prompt = <<<EOT
You are a smart business analyst. Analyze the provided context and answer the user's question.

CONTEXT:
$context

QUESTION: {$request->question}

Answer with clear insights (not raw data). Be concise and helpful.
EOT;

    try {
        $response = \Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('OPENROUTER_API_URL') . '/chat/completions', [
            'model' => env('OPENROUTER_MODEL'),
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful business analyst.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ])->json();

        $reply = $response['choices'][0]['message']['content'] ?? 'No response.';

        return response()->json(['answer' => $reply]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'AI request failed: ' . $e->getMessage()], 500);
    }
}


}
