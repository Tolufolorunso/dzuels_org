<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GenerateAiContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'field_name' => ['required', 'string', 'max:100'],
            'field_label' => ['nullable', 'string', 'max:255'],
            'page_context' => ['nullable', 'string', 'max:255'],
            'section_context' => ['nullable', 'string', 'max:255'],
            'sibling_data' => ['nullable', 'array'],
            'prompt_tweak' => ['nullable', 'string', 'max:500'],
            'image_path' => ['nullable', 'string', 'max:500'],
        ];
    }
}
