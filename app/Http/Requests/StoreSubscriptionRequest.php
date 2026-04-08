<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
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
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'plan_price_id' => ['required', 'integer', 'exists:plan_prices,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
