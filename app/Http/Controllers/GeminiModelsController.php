<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class GeminiModelsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $apiKey = config('ai.gemini_api_key');
        if (!$apiKey) {
            return response()->json([
                'ok' => false,
                'error' => 'Missing GEMINI_API_KEY.',
            ], 400);
        }

        $response = Http::timeout(30)
            ->withQueryParameters(['key' => $apiKey])
            ->get('https://generativelanguage.googleapis.com/v1beta/models');

        if (!$response->ok()) {
            return response()->json([
                'ok' => false,
                'status' => $response->status(),
                'body' => $response->json(),
            ], 502);
        }

        return response()->json([
            'ok' => true,
            'models' => $response->json('models'),
        ]);
    }
}
