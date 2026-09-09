<?php

namespace App\Http\Requests\Puskesmas;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengaturanRequest extends FormRequest
{
    public function authorize()
    {
        // Only puskesmas role can update its own profile
        return auth()->check() && auth()->user()->role === 'puskesmas';
    }

    public function rules()
    {
        return [
            'kepala_puskesmas' => ['required', 'string', 'max:255'],
            'no_telp'          => ['nullable', 'regex:/^[0-9]{8,15}$/'],
            'alamat'           => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'kepala_puskesmas.required' => 'Nama kepala puskesmas wajib diisi.',
            'no_telp.regex'             => 'Nomor telepon harus 8–15 digit angka saja.',
            'alamat.required'           => 'Alamat operasional wajib diisi.',
        ];
    }
}
