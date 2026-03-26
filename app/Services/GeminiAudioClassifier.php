<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
class GeminiAudioClassifier
{
    public function classify(string $audioBinary, string $mimeType): array
    {
        $apiKey = config('ai.gemini_api_key');
        if (!$apiKey) {
            return [
                'ok' => false,
                'error' => 'Missing GEMINI_API_KEY.',
            ];
        }

        $model = config('ai.gemini_model', 'gemini-1.5-flash');
        $model = preg_replace('/^models\//', '', $model);
        $categories = config('ai.categories', []);
        $categoriesText = $categories ? implode(', ', $categories) : 'sem categorias definidas';

        $prompt = "Transcreva o áudio em pt-BR e classifique o problema em uma das categorias: "
            . $categoriesText
            . ". Responda SOMENTE em JSON com as chaves: transcript, category. "
            . "A categoria deve ser exatamente uma das opções.";

        $response = Http::timeout(60)
            ->withQueryParameters(['key' => $apiKey])
            ->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data' => base64_encode($audioBinary),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                    ],
                ]
            );

        if (!$response->ok()) {
            return [
                'ok' => false,
                'error' => 'Gemini request failed.',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
        if (!is_string($text)) {
            return [
                'ok' => false,
                'error' => 'Gemini response missing text.',
                'raw' => $response->json(),
            ];
        }

        $payload = $this->extractJson($text);
        if (!is_array($payload)) {
            return [
                'ok' => false,
                'error' => 'Failed to parse JSON from Gemini.',
                'raw_text' => $text,
            ];
        }

        return [
            'ok' => true,
            'data' => $payload,
            'raw_text' => $text,
        ];
    }

    private function extractJson(string $text): ?array
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start === false || $end === false || $end <= $start) {
            return null;
        }

        $json = substr($text, $start, $end - $start + 1);

        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : null;
    }
}
