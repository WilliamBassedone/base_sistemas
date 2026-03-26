<?php

namespace App\Http\Controllers;

use App\Services\GeminiAudioClassifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AudioTriageController extends Controller
{
    public function __invoke(Request $request, GeminiAudioClassifier $classifier): JsonResponse
    {
        $validated = $request->validate([
            'audio' => ['required', 'file', 'mimes:wav,mp3,m4a,mp4,webm'],
        ]);

        $file = $validated['audio'];
        $mimeType = $file->getMimeType() ?? 'audio/wav';
        $mimeType = explode(';', $mimeType, 2)[0];
        if ($mimeType === 'video/webm') {
            $mimeType = 'audio/webm';
        }
        $binary = file_get_contents($file->getRealPath());

        $result = $classifier->classify($binary, $mimeType);
        if (!$result['ok']) {
            return response()->json([
                'ok' => false,
                'error' => $result['error'] ?? 'Unknown error.',
                'details' => $result,
            ], 502);
        }

        return response()->json([
            'ok' => true,
            'transcript' => $result['data']['transcript'] ?? null,
            'category' => $result['data']['category'] ?? null,
        ]);
    }
}
