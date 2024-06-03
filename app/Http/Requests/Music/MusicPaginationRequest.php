<?php

namespace App\Http\Requests\Music;

use Illuminate\Foundation\Http\FormRequest;

class MusicPaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'name' => ['nullable', 'string', 'max:50'],
            'type_id' => ['nullable', 'integer'],
            'page' => ['required', 'integer', 'min:1', 'max:200'],
            'size' => ['required', 'integer', 'min:1', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'string' => 'The :attribute is must string.',
            'required' => 'The :attribute is must.',
            'integer' => 'The :attribute is must integer.',
        ];
    }
}
