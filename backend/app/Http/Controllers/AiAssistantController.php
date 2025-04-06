<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AiAssistantController extends Controller
{
    public function handle(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input provided'], 400);
        }

        $prompt = $request->input('prompt');
        $apiKey = config('services.openai.key'); // Reads from .env

        // Prepare request payload for OpenAI API
        $data = [
            'model' => 'gpt-3.5-turbo', // Change to 'gpt-4' if available
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        // Make the API call to OpenAI
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', $data);

        if ($response->failed()) {
            return response()->json(['error' => 'OpenAI API request failed'], 500);
        }

        $result = $response->json();
        $reply = $result['choices'][0]['message']['content'] ?? 'No reply available';
        $timestamp = Carbon::now()->toDateTimeString();

        return response()->json([
            'reply'     => $reply,
            'timestamp' => $timestamp,
        ]);
    }

    public function report(Request $request)
    {
        // Check for dynamic data input; use it if provided, else fallback to dummy dataset.
        $data = $request->input('data');
        if (!$data) {
            $data = [
                [
                    "employee_id" => "E001",
                    "name" => "Jane Doe",
                    "team" => "Sales",
                    "engagement_score" => 78,
                    "training_completion" => 100,
                    "attendance_rate" => 92,
                ],
                [
                    "employee_id" => "E002",
                    "name" => "John Smith",
                    "team" => "Sales",
                    "engagement_score" => 65,
                    "training_completion" => 80,
                    "attendance_rate" => 85,
                ],
                [
                    "employee_id" => "E003",
                    "name" => "Sara Khan",
                    "team" => "Sales",
                    "engagement_score" => 50,
                    "training_completion" => 60,
                    "attendance_rate" => 70,
                ],
            ];
        }

        // Optional: Validate that $data is an array.
        if (!is_array($data)) {
            return response()->json(['error' => 'Data must be an array'], 400);
        }

        // Format the data as a pretty-printed JSON string.
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        // Create a structured prompt for the AI.
        $prompt = "Given this JSON data:\n$jsonData\n\nSummarize any concerning trends for management.";

        $apiKey = config('services.openai.key'); // Reads from .env

        // Prepare the payload for the OpenAI API.
        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        // Make the API call.
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', $payload);

        if ($response->failed()) {
            return response()->json(['error' => 'OpenAI API request failed'], 500);
        }

        $result = $response->json();
        $summary = $result['choices'][0]['message']['content'] ?? 'No summary available';

        // OPTIONAL: Log the request and response (if you set up a logging table).
        // AiLog::create([
        //     'prompt' => $prompt,
        //     'reply'  => $summary,
        // ]);

        return response()->json(['summary' => $summary]);
    }
}
