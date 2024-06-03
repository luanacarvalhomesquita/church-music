<?php

namespace App\Http\Requests\Type;

use Illuminate\Foundation\Http\FormRequest;

class TypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'name' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute is must.',
            'string' => 'The :attribute is must string.',
        ];
    }
}
