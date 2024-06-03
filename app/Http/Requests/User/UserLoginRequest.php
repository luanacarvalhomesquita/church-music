<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class UserLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'string' => Lang::get('request-errors.string', array('attribute' => ':attribute')),
            'password.required' => Lang::get('request-errors.password_password', array('attribute' => ':attribute')),
            'email.email' => Lang::get('request-errors.email', array('attribute' => ':attribute')),
            'email.required' => Lang::get('request-errors.email_required', array('attribute' => ':attribute')),
        ];
    }
}
