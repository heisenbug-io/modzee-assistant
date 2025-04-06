<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\AiLog;
use Carbon\Carbon;

class AiAssistantController extends Controller
{
    public function handle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input provided'], 400);
        }

        $prompt = $request->input('prompt');
        $apiKey = config('services.gemini.key');

        // 🔁 Fetch entire conversation history from DB (in order)
        $logs = AiLog::orderBy('created_at')->get();

        $conversation = [];

        foreach ($logs as $log) {
            $conversation[] = [
                'role' => 'user',
                'parts' => [['text' => $log->prompt]],
            ];
            $conversation[] = [
                'role' => 'model',
                'parts' => [['text' => $log->reply]],
            ];
        }

        // ➕ Add new user prompt
        $conversation[] = [
            'role' => 'user',
            'parts' => [['text' => $prompt]],
        ];

        $payload = [
            'contents' => $conversation,
        ];

        $response = Http::withHeaders([
            'Content-Type'     => 'application/json',
            'X-goog-api-key'   => $apiKey,
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', $payload);

        if ($response->failed()) {
            Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
                'prompt' => $prompt,
            ]);
            return response()->json(['error' => 'Gemini API request failed'], 500);
        }

        $result = $response->json();
        $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No reply available';

        // ✅ Store new entry in DB
        AiLog::create([
            'prompt' => $prompt,
            'reply'  => $reply,
        ]);

        return response()->json([
            'reply'     => $reply,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    public function reset(Request $request)
    {
        // 🧹 Clear all history from DB
        AiLog::truncate();

        return response()->json(['message' => 'Conversation reset']);
    }

    public function logs()
    {
        $logs = \App\Models\AiLog::orderBy('created_at')->get();

        $conversation = [];

        foreach ($logs as $log) {
            $conversation[] = [
                'role' => 'user',
                'text' => $log->prompt,
                'timestamp' => $log->created_at->toDateTimeString(),
            ];
            $conversation[] = [
                'role' => 'model',
                'text' => $log->reply,
                'timestamp' => $log->created_at->toDateTimeString(),
            ];
        }

        return response()->json($conversation);
    }
}
