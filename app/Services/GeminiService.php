<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Send an audio file to Gemini API to transcribe it and extract action items in structured JSON format.
     *
     * @param string $filePath Absolute path to the audio file
     * @param string $mimeType Mime type of the audio file (e.g. audio/webm)
     * @return array Decoded JSON response containing transcription and action_items
     * @throws \Exception
     */
    public static function extractFromAudio($filePath, $mimeType)
    {
        $apiKey = config('services.gemini.api_key');
        $configuredModel = config('services.gemini.model', 'gemini-3.5-flash');

        if (!$apiKey) {
            Log::error('Gemini API key is not configured.');
            throw new \Exception('Gemini API key is not configured.');
        }

        if (!file_exists($filePath)) {
            Log::error("Audio file not found at path: {$filePath}");
            throw new \Exception('Audio file not found.');
        }

        $audioData = base64_encode(file_get_contents($filePath));

        // Prompt instructing Gemini to transcribe and format as JSON
        $prompt = "Analisis audio rekaman rapat ini. Buat transkripsi lengkap yang akurat dalam bahasa Indonesia untuk notulen, dan identifikasi semua poin tindak lanjut (action items) yang disebutkan. Kembalikan respons dalam format JSON dengan struktur berikut:\n" .
            "{\n" .
            "  \"transcription\": \"Teks transkripsi lengkap...\",\n" .
            "  \"action_items\": [\n" .
            "    {\n" .
            "      \"description\": \"Deskripsi tugas atau tindak lanjut...\",\n" .
            "      \"priority\": \"Low\" | \"Medium\" | \"High\" | \"Critical\",\n" .
            "      \"category\": \"Develop\" | \"Non Develop\",\n" .
            "      \"target_date\": \"YYYY-MM-DD atau null jika tidak disebutkan\"\n" .
            "    }\n" .
            "  ]\n" .
            "}";

        // List of models to try, starting with the user's preference and falling back if needed
        $models = [$configuredModel, 'gemini-3.5-flash', 'gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-1.5-flash'];
        $models = array_values(array_unique($models)); // Remove duplicates

        $lastException = null;

        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            Log::info("Attempting Gemini audio extraction with model: {$model}");

            try {
                // Large timeout because transcribing audio can take some time
                // using withoutVerifying() to bypass local SSL certificate issue (cURL error 77)
                $response = Http::timeout(90)->withoutVerifying()->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inlineData' => [
                                        'mimeType' => $mimeType,
                                        'data' => $audioData
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json'
                    ]
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    
                    Log::info("Gemini Model {$model} Response successfully received.");

                    // Strip markdown block symbols if present
                    $cleanText = trim($text);
                    if (strpos($cleanText, '```') === 0) {
                        $cleanText = preg_replace('/^```(?:json)?\n?/i', '', $cleanText);
                        $cleanText = preg_replace('/```$/', '', $cleanText);
                        $cleanText = trim($cleanText);
                    }
                    
                    $decoded = json_decode($cleanText, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Attempt fallback JSON extraction using bracket matching
                        $firstBrace = strpos($cleanText, '{');
                        $lastBrace = strrpos($cleanText, '}');
                        if ($firstBrace !== false && $lastBrace !== false) {
                            $jsonSub = substr($cleanText, $firstBrace, $lastBrace - $firstBrace + 1);
                            $decoded = json_decode($jsonSub, true);
                        }
                    }

                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        return $decoded;
                    } else {
                        Log::error("Failed to decode JSON from Gemini response: {$text}");
                        throw new \Exception("Response from model {$model} was not valid JSON.");
                    }
                }

                $errorMsg = $response->body();
                Log::warning("Gemini model {$model} returned status {$response->status()}: {$errorMsg}");
                $lastException = new \Exception("Gemini API error from model {$model} (Status {$response->status()}): {$errorMsg}");

            } catch (\Exception $e) {
                Log::warning("Exception occurred with model {$model}: " . $e->getMessage());
                $lastException = $e;
            }
        }

        // If all models failed, throw the last exception
        throw $lastException ?: new \Exception('Failed to extract transcription and action items from audio using Gemini API.');
    }
}
