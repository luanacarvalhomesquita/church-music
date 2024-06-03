<?php

namespace App\Http\Requests\Singer;

use Illuminate\Foundation\Http\FormRequest;

class SingerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'name' => ['required', 'string'],
            'page' => ['integer', 'min:1', 'max:200'],
            'size' => ['integer', 'min:1', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute is must.',
            'string' => 'The :attribute is must string.',
            'integer' => 'The :attribute is must integer.',
        ];
    }
}
