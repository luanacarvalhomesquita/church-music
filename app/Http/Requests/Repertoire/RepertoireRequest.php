<?php

namespace App\Http\Requests\Repertoire;

use Illuminate\Foundation\Http\FormRequest;

class RepertoireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'title' => ['required', 'string', 'max:100', 'min:2'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'musics' => [
                'id' => ['nullable', 'integer'],
                'tone' => ['nullable', 'string'],
            ],
            'singers' => [
                'id' => ['nullable', 'integer'],
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute is must.',
            'integer' => 'The :attribute is must integer.',
            'string' => 'The :attribute is must string.',
            'date' => 'The :attribute is must date.',
        ];
    }
}
