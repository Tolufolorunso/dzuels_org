<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:events,slug'],
            'summary' => ['required', 'string', 'max:1000'],
            'description' => ['required', 'string'],
            'event_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:255'],
            'banner_image' => $this->hasFile('banner_image')
                ? ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240']
                : ['nullable', 'string', 'max:255'],
            'banner_image_path' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable'],
            'is_registration_open' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
