<?php

namespace App\Http\Requests\Puskesmas;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'     => ['required', 'string', 'max:255'],
            'no_hp'    => ['nullable', 'string', 'max:30'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
