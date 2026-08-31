<?php

namespace App\Http\Requests\Kader;

use Illuminate\Foundation\Http\FormRequest;

class StoreBalitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'                 => 'required|string|max:255',
            'nik'                  => 'required|digits:16|unique:balitas,nik',
            'no_bpjs'              => 'nullable|string|max:30',
            'jenis_kelamin'        => 'required|in:L,P',
            'tanggal_lahir'        => 'required|date',
            'berat_lahir'          => 'nullable|numeric|min:0.5|max:10',
            'panjang_lahir'        => 'nullable|numeric|min:20|max:80',
            'lingkar_kepala_lahir' => 'nullable|numeric|min:15|max:50',
            // Orang Tua
            'no_kk'                => 'nullable|digits:16',
            'nama_ibu'             => 'required|string|max:255',
            'nik_ibu'              => 'nullable|digits:16',
            'pekerjaan_ibu'        => 'nullable|string|max:100',
            'no_hp'                => 'required|string|max:20',
            'nama_ayah'            => 'nullable|string|max:255',
            'nik_ayah'             => 'nullable|digits:16',
            'pekerjaan_ayah'       => 'nullable|string|max:100',
            'desa'                 => 'nullable|string|max:255',
            'kecamatan'            => 'nullable|string|max:255',
        ];
    }
}
