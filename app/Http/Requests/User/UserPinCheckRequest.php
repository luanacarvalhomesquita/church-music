<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class UserPinCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pin' => ['required', 'string', 'exists:password_resets'],
            'email' => ['required', 'email', 'exists:users'],
        ];
    }

    public function messages(): array
    {
        return [
            'string' => Lang::get('request-errors.string'),
            'email.email' => Lang::get('request-errors.email'),
            'email.required' => Lang::get('request-errors.email_required'),
            'pin.required' => Lang::get('request-errors.pin_required'),
            'pin.exists' => Lang::get('request-errors.pin_exists'),
            'email.exists' => Lang::get('request-errors.email_exists'),
        ];
    }
}
