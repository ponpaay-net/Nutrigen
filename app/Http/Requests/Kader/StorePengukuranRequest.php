<?php

namespace App\Http\Requests\Kader;

use Illuminate\Foundation\Http\FormRequest;

class StorePengukuranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'balita_id'       => 'required|exists:balitas,id',
            'tanggal_ukur'    => 'required|date',
            'berat_badan'     => 'required|numeric|min:1|max:999.99',
            'tinggi_badan'    => 'required|numeric|min:10|max:999.99',
            'lingkar_kepala'  => 'nullable|numeric|min:10|max:99.99',
            'asi_eksklusif'   => 'nullable',
            'status_kenaikan' => 'nullable|string|max:10',
            'catatan_kader'   => 'nullable|string|max:500',
        ];
    }
}
