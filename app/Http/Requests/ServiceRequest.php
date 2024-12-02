<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'provider' => 'required|string|max:255',
            'representative' => 'required|string|max:255',
            'serviceType' => 'required|string|max:255',
            'coveredArea' => 'required|string|max:255',
            'contactInform' => [
                'required',
                'string',
                'max:15',
                'regex:/^(00|\+)[0-9]{6,14}$/', // Phone number must start with 00 or +
            ],
            'availableTime' => [
                'required',
                'string',
                'regex:/^([01]\d|2[0-3]):([0-5]\d)$/', // 24-hour format HH:MM
            ],
            'moreInfo' => 'nullable|string',
        ];
    }
}
