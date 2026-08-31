<?php

namespace App\Http\Requests\Kader;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'         => 'required|string|max:255',
            'lokasi'        => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
            'catatan'       => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'         => 'Judul kegiatan wajib diisi.',
            'lokasi.required'        => 'Lokasi pelaksanaan wajib diisi.',
            'tanggal.required'       => 'Tanggal kegiatan wajib diisi.',
            'waktu_mulai.required'   => 'Jam mulai kegiatan wajib diisi.',
            'waktu_selesai.required' => 'Jam selesai kegiatan wajib diisi.',
        ];
    }
}
