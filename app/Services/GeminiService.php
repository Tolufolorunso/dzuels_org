<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;

    protected string $model;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    /**
     * Create a new GeminiService instance.
     */
    public function __construct(?string $apiKey = null, ?string $model = null)
    {
        $this->apiKey = $apiKey ?: Setting::getGeminiApiKey();
        $this->model = $model ?: Setting::getGeminiModel();
    }

    /**
     * Set a custom API key for this instance.
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = trim($apiKey);

        return $this;
    }

    /**
     * Set a custom model for this instance.
     */
    public function setModel(string $model): self
    {
        $this->model = trim($model);

        return $this;
    }

    /**
     * Test the API connection to Google Gemini.
     *
     * @return array{success: bool, message: string}
     */
    public function testConnection(?string $testKey = null, ?string $testModel = null): array
    {
        $key = trim((string) ($testKey ?: $this->apiKey));
        $model = trim((string) ($testModel ?: $this->model));

        if ($key === '') {
            return [
                'success' => false,
                'message' => 'Gemini API Key is missing. Please provide a valid key.',
            ];
        }

        $endpoint = "{$this->baseUrl}/{$model}:generateContent?key={$key}";

        try {
            $response = Http::timeout(10)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Hello. Respond with OK.'],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 5,
                    'temperature' => 0.1,
                ],
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => "Successfully connected to Google Gemini ({$model}).",
                ];
            }

            $errorData = $response->json('error');
            $errorMsg = is_array($errorData) && isset($errorData['message'])
                ? $errorData['message']
                : ($response->body() ?: "HTTP {$response->status()}");

            return [
                'success' => false,
                'message' => "Connection failed ({$response->status()}): {$errorMsg}",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Connection error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Generate text content using Google Gemini.
     *
     * @param  string  $prompt  The user prompt or instruction
     * @param  string|null  $systemInstruction  Optional custom system prompt (defaults to Dzuels charity prompt)
     * @param  string|null  $imagePath  Optional relative or absolute image path for multimodal vision analysis
     *
     * @throws \RuntimeException
     */
    public function generateText(string $prompt, ?string $systemInstruction = null, ?string $imagePath = null): string
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('Google Gemini API Key is not configured. Please add your key in Site Settings.');
        }

        $endpoint = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";
        $systemPrompt = $systemInstruction ?: $this->buildDefaultSystemPrompt();

        $parts = [];

        // If an image is provided, inspect and attach as multimodal inline data
        if ($imagePath) {
            $encodedImage = $this->encodeImage($imagePath);
            if ($encodedImage) {
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $encodedImage['mime_type'],
                        'data' => $encodedImage['data'],
                    ],
                ];
            }
        }

        $parts[] = ['text' => $prompt];

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemPrompt],
                ],
            ],
            'contents' => [
                [
                    'parts' => $parts,
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topP' => 0.95,
                'maxOutputTokens' => 1024,
            ],
        ];

        try {
            $response = Http::timeout(30)->post($endpoint, $payload);

            if (! $response->successful()) {
                $errorData = $response->json('error');
                $errorMsg = is_array($errorData) && isset($errorData['message'])
                    ? $errorData['message']
                    : ($response->body() ?: "HTTP {$response->status()}");

                Log::error('Gemini API generation failed', [
                    'status' => $response->status(),
                    'error' => $errorMsg,
                    'model' => $this->model,
                ]);

                throw new \RuntimeException("Gemini API Error: {$errorMsg}");
            }

            $candidates = $response->json('candidates');
            if (empty($candidates) || ! isset($candidates[0]['content']['parts'][0]['text'])) {
                throw new \RuntimeException('Gemini returned an empty or invalid response.');
            }

            $generatedText = trim($candidates[0]['content']['parts'][0]['text']);

            // Strip wrapping double quotes if the model wrapped the entire output in quotes
            if (str_starts_with($generatedText, '"') && str_ends_with($generatedText, '"') && strlen($generatedText) > 2) {
                $generatedText = substr($generatedText, 1, -1);
            }

            return trim($generatedText);
        } catch (\Throwable $e) {
            if ($e instanceof \RuntimeException) {
                throw $e;
            }

            Log::error('Gemini connection exception: '.$e->getMessage());
            throw new \RuntimeException('Failed to communicate with Google Gemini: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Build the foundation brand context and British English system prompt.
     */
    public function buildDefaultSystemPrompt(): string
    {
        return <<<'PROMPT'
You are the AI writing and content assistant for Dzuels Educational Foundation (https://dzuelsfoundation.org), a registered Nigerian charity founded on 22 June 2015.

Foundation Profile & Context:
- Mission: Providing free educational support, literacy acquisition, and digital skills training for indigent Nigerian children and youth, creating awareness of social vices, and offering social welfare and community relief.
- Primary Community: Ijero-Ekiti and Ekiti State, Southwest Nigeria.
- Flagship Initiatives: Ijero Tech Day (ITD), Famoyinka Memorial Children's Library, Reading Competitions, Spelling Bees, Essay Contests, Girls in Tech Day, and WDADA Community Outreach.
- Core Values: Dedication, Respect, Novelty, Integrity, Collaboration, Excellence, and Resilience (DRNICER).

Style & Language Instructions:
1. Always use British / Commonwealth English spelling (e.g., programme, organise, colour, centre, modelled, honour, skilful).
2. Maintain an inspiring, dignified, authentic, and professional non-profit tone.
3. Keep answers concise, factual, and directly suited to the requested field and context.
4. Output ONLY the requested copy or text. Never include conversational prefixes (such as "Here is your text:" or "Sure!"), commentary, or wrapping formatting unless requested.
PROMPT;
    }

    /**
     * Locate, read, and base64-encode an image for multimodal vision analysis.
     *
     * @return array{mime_type: string, data: string}|null
     */
    public function encodeImage(string $path): ?array
    {
        $normalizedPath = ltrim($path, '/\\');
        $fullPath = null;

        // Try absolute path, public_path, or base_path
        if (File::exists($path) && is_file($path)) {
            $fullPath = $path;
        } elseif (File::exists(public_path($normalizedPath)) && is_file(public_path($normalizedPath))) {
            $fullPath = public_path($normalizedPath);
        } elseif (File::exists(base_path($normalizedPath)) && is_file(base_path($normalizedPath))) {
            $fullPath = base_path($normalizedPath);
        }

        if (! $fullPath) {
            return null;
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mimeType = match ($extension) {
            'webp' => 'image/webp',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'image/jpeg',
        };

        try {
            $bytes = File::get($fullPath);
            if (empty($bytes)) {
                return null;
            }

            return [
                'mime_type' => $mimeType,
                'data' => base64_encode($bytes),
            ];
        } catch (\Throwable $e) {
            Log::warning("GeminiService: failed to read image at {$fullPath}: ".$e->getMessage());

            return null;
        }
    }
}
