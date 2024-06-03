<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class UserForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => Lang::get('request-errors.email', array('attribute' => ':attribute')),
            'email.required' => Lang::get('request-errors.email_required', array('attribute' => ':attribute')),
        ];
    }
}
