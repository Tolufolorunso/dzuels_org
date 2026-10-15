<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryPhotoRequest extends FormRequest
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
            'photos' => ['required_without:photo', 'array', 'min:1', 'max:20'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'photo' => ['required_without:photos', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:10'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'photos.max' => 'You cannot upload more than 20 photos in a single request.',
            'photos.*.max' => 'Each photo must not exceed 10 MB.',
            'photo.max' => 'The photo must not exceed 10 MB.',
        ];
    }
}
