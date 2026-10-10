<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVolunteerApplicationRequest extends FormRequest
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
        return [
            '_hp_company' => ['prohibited'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'age_band' => ['required', 'string', 'in:below_18,18_to_55,above_55'],
            'work_status' => ['required', 'string', 'in:employed,self_employed,unemployed,student'],
            'skills' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            '_hp_company.prohibited' => 'Spam submission detected.',
            'age_band.in' => 'Please select a valid age band.',
            'work_status.in' => 'Please select a valid work status.',
        ];
    }
}
