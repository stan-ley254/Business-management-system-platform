<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HuggingFaceController extends Controller
{

        public function index()
    {
        return view('chat');
    }
    public function query(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $response = Http::withToken(env('HUGGINGFACE_API_KEY'))
            ->timeout(30)
            ->post("'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.1'
", [
                'inputs' => $request->prompt,
            ]);

        $data = $response->json();

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 400);
        }

        return response()->json(['response' => $data[0]['generated_text'] ?? 'No response']);
    }
}
