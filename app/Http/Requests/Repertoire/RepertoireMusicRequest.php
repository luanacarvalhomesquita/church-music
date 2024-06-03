<?php

namespace App\Http\Requests\Repertoire;

use Illuminate\Foundation\Http\FormRequest;

class RepertoireMusicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            "musics" => [
                'id' => ['required', 'integer'],
                'tone' => ['required', 'string'],
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute is must.',
            'integer' => 'The :attribute is must integer.',
            'string' => 'The :attribute is must string.',
        ];
    }
}
