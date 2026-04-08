<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            //
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',       
            'trial_days' => 'integer|min:0',
            'prices' => 'sometimes|required|array|min:1',
            'prices.*.billing_cycle' => 'required|in:monthly,yearly',
            'prices.*.currency' => 'required|string|max:3',
            'prices.*.amount' => 'required|numeric|min:0',  
        ];
    }
}
