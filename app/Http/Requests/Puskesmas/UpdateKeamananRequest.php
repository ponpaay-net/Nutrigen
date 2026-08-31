<?php

namespace App\Http\Requests\Puskesmas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeamananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ];
    }
}
