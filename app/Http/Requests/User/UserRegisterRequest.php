<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'min:2'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
        ];
    }

    public function messages(): array
    {
        return [
            'string' => Lang::get('request-errors.string'),
            'name.required' => Lang::get('request-errors.name_required'),
            'name.min' => Lang::get('request-errors.name_min', array('min' => ':min')),
            'name.max' => Lang::get('request-errors.name_max', array('max' => ':max')),
            'password.required' => Lang::get('request-errors.password_password'),
            'password.confirmed' => trans('request-errors.password_confirmed'),
            'email.email' => Lang::get('request-errors.email'),
            'email.required' => Lang::get('request-errors.email_required'),
            'email.unique' => Lang::get('request-errors.unique_email'),
        ];
    }
}
