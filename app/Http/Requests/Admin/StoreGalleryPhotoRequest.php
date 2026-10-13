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
            'photos' => ['required_without:photo', 'array'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'photo' => ['required_without:photos', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:10'],
        ];
    }
}
