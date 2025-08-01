<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadedSale;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Auth;

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
            return back()->with('error', 'No sales data available.');
        }

        $csvContent = Storage::get($upload->file_path);

        // Keep prompt concise but context-rich
        $prompt = <<<EOT
You are a smart business analyst. The user will ask you questions about sales data.

SALES DATA (CSV format):
$csvContent

QUESTION: {$request->question}
Answer in plain language. Be concise and helpful.
EOT;

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful business analyst.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $reply = $response->choices[0]->message->content ?? 'No response.';

        return view('chat.business', [
            'question' => $request->question,
            'answer' => $reply,
        ]);
    }
}
