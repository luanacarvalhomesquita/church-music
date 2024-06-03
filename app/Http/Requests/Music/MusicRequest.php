<?php

namespace App\Http\Requests\Music;

use Illuminate\Foundation\Http\FormRequest;

class MusicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'name' => ['required', 'string', 'max:100', 'min:2'],
            'description' => ['nullable', 'string', 'max:100'],
            'main_version' => ['required', 'string', 'max:100'],
            'played' => ['nullable', 'bool'],
            'type_id' => ['nullable', 'integer'],
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
