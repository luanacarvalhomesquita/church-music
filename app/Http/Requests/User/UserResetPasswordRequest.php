<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Password;

class UserResetPasswordRequest extends FormRequest
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
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
        ];
    }

    public function messages(): array
    {
        return [
            'string' => Lang::get('request-errors.string'),
            'pin.required' => Lang::get('request-errors.pin_required'),
            'pin.exists' => Lang::get('request-errors.pin_exists'),
            'password.required' => Lang::get('request-errors.password_password'),
            'password.confirmed' => trans('request-errors.password_confirmed'),
            'email.email' => Lang::get('request-errors.email'),
            'email.required' => Lang::get('request-errors.email_required'),
        ];
    }
}
