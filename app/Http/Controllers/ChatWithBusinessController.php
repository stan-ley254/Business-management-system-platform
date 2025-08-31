<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadedSale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
    $upload = UploadedSale::where('business_id', $businessId)->latest()->first();

    if (!$upload || !Storage::exists($upload->file_path)) {
        return response()->json(['error' => 'No sales data available.'], 400);
    }

    $csvContent = Storage::get($upload->file_path);

    $prompt = <<<EOT
You are a smart business analyst. The user will ask you questions about sales data.

SALES DATA (CSV format):
$csvContent

QUESTION: {$request->question}
Answer in plain language. Be concise and helpful.
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
        return response()->json(['error' => 'Failed to contact AI service: ' . $e->getMessage()], 500);
    }
}

}
