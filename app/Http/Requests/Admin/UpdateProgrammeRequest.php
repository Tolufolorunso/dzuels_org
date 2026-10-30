<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $programme = $this->route('programme');
        $programmeId = is_object($programme) ? $programme->id : $programme;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('programmes', 'slug')->ignore($programmeId)],
            'summary' => ['required', 'string', 'max:1000'],
            'body' => ['required', 'string'],
            'cover_image' => $this->hasFile('cover_image')
                ? ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:8192']
                : ['nullable', 'string', 'max:255'],
            'partner_name' => ['nullable', 'string', 'max:255'],
            'gallery_album_id' => ['nullable', 'integer', 'exists:gallery_albums,id'],
            'gallery_photos' => ['nullable'],
            'new_gallery_photos' => ['nullable', 'array'],
            'new_gallery_photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
