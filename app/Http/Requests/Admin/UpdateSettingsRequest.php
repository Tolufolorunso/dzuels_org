<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            // Impact metrics
            'stat_ict_students' => ['required', 'integer', 'min:0'],
            'stat_literacy_students' => ['required', 'integer', 'min:0'],
            'stat_schools_supported' => ['required', 'integer', 'min:0'],
            'stat_feed_mode' => ['required', 'in:manual,api'],
            'stat_api_endpoint' => ['nullable', 'url', 'max:255'],

            // Contact
            'contact_phone_1' => ['required', 'string', 'max:50'],
            'contact_phone_2' => ['nullable', 'string', 'max:50'],
            'contact_phone_3' => ['nullable', 'string', 'max:50'],
            'contact_phone_4' => ['nullable', 'string', 'max:50'],
            'contact_phone_5' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['required', 'email', 'max:100'],
            'contact_address' => ['required', 'string', 'max:255'],
            'contact_working_hours' => ['nullable', 'string', 'max:100'],
            'contact_timezone' => ['nullable', 'string', 'max:50'],
            'contact_working_days_note' => ['nullable', 'string', 'max:100'],

            // Banking & Giving
            'flutterwave_url' => ['required', 'url', 'max:255'],
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account_name' => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:30'],

            // Social & Legal
            'registration_number' => ['nullable', 'string', 'max:50'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
        ];
    }
}
