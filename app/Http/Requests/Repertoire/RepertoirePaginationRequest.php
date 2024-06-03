<?php

namespace App\Http\Requests\Repertoire;

use Illuminate\Foundation\Http\FormRequest;

class RepertoirePaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'page' => ['required', 'integer', 'min:1', 'max:200'],
            'size' => ['required', 'integer', 'min:1', 'max:200'],
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
