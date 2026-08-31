<?php

namespace App\Http\Requests\Puskesmas;

use Illuminate\Foundation\Http\FormRequest;

class StorePosyanduRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'         => ['required', 'string', 'max:255'],
            'desa_kelurahan' => ['required', 'string', 'max:255'],
            'alamat'       => ['nullable', 'string'],
        ];
    }
}
