<?php

namespace App\Http\Requests\SingerMusic;

use Illuminate\Foundation\Http\FormRequest;

class SingerMusicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'tone' => ['string'],
            'version' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'string' => 'The :attribute is must string.',
        ];
    }
}
