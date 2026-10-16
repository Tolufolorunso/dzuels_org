<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenerateAiContentRequest;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AiContentController extends Controller
{
    /**
     * Generate context-aware text content using Google Gemini.
     */
    public function generate(GenerateAiContentRequest $request, GeminiService $geminiService): JsonResponse
    {
        $validated = $request->validated();

        $fieldName = $validated['field_name'];
        $fieldLabel = ! empty($validated['field_label']) ? $validated['field_label'] : ucfirst(str_replace('_', ' ', $fieldName));
        $pageContext = $validated['page_context'] ?? 'Admin CMS';
        $sectionContext = $validated['section_context'] ?? 'Content Section';
        $siblingData = array_filter((array) ($validated['sibling_data'] ?? []), function ($v) {
            return $v !== null && $v !== '';
        });
        $promptTweak = trim((string) ($validated['prompt_tweak'] ?? ''));
        $imagePath = $validated['image_path'] ?? null;

        $promptParts = [];
        $promptParts[] = "Target Input Field: \"{$fieldLabel}\" (identifier: {$fieldName}).";
        $promptParts[] = "Location / Scope: Page: \"{$pageContext}\" | Section / Area: \"{$sectionContext}\".";

        if (! empty($siblingData)) {
            $promptParts[] = 'Existing Context & Associated Values:';
            foreach ($siblingData as $key => $val) {
                if (is_scalar($val)) {
                    $keyLabel = ucfirst(str_replace('_', ' ', (string) $key));
                    $promptParts[] = "  - {$keyLabel}: \"{$val}\"";
                }
            }
        }

        if ($imagePath) {
            $promptParts[] = 'An authentic foundation photograph is attached. Carefully inspect the visual details in the image (such as activities, participants, books, or environment) so the generated text accurately and factually reflects the photograph.';
        }

        if ($promptTweak !== '') {
            $promptParts[] = "Staff Custom Direction: \"{$promptTweak}\".";
        }

        // Specific guidance based on field type
        $lowerName = strtolower($fieldName);
        if (str_contains($lowerName, 'alt') || str_contains($lowerName, 'alt_text')) {
            $promptParts[] = "Task: Write concise, accessible alternative text (alt text) describing the scene or subject for screen readers and search engines. Do not prefix with 'Photo of' or 'Image of'. Keep under 25 words.";
        } elseif (str_contains($lowerName, 'caption')) {
            $promptParts[] = "Task: Write an engaging, warm, descriptive photo caption detailing what is happening in the picture, highlighting the foundation's impact and participants.";
        } elseif (str_contains($lowerName, 'headline') || str_contains($lowerName, 'title')) {
            $promptParts[] = 'Task: Write a compelling, clear, inspiring headline or title. Keep it punchy and memorable.';
        } elseif (str_contains($lowerName, 'subheadline') || str_contains($lowerName, 'kicker') || str_contains($lowerName, 'summary')) {
            $promptParts[] = 'Task: Write a concise 1 to 2 sentence summary or subheadline that invites the reader in.';
        } elseif (str_contains($lowerName, 'body') || str_contains($lowerName, 'description') || str_contains($lowerName, 'bio')) {
            $promptParts[] = 'Task: Write an inspiring, well-structured paragraph or detailed description. Emphasize community empowerment, education, and impact.';
        } else {
            $promptParts[] = "Task: Generate polished, professional copy tailored specifically for the \"{$fieldLabel}\" input.";
        }

        $fullPrompt = implode("\n", $promptParts);

        try {
            $generatedText = $geminiService->generateText($fullPrompt, null, $imagePath);

            return response()->json([
                'success' => true,
                'text' => $generatedText,
            ]);
        } catch (\Throwable $e) {
            Log::warning('AI Content Generation Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
