<?php

namespace App\Http\Requests\Repertoire;

use Illuminate\Foundation\Http\FormRequest;

class RepertoireSingerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            "singers" => [
                'id' => ['required', 'integer'],
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute is must.',
            'integer' => 'The :attribute is must integer.',
        ];
    }
}
