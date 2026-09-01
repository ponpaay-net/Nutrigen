<?php

namespace App\Http\Requests\Kader;

use App\Models\Balita;
use App\Services\GrowthCalculationService;
use Carbon\Carbon;
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
            'berat_badan'     => 'required|numeric|min:0.3|max:60',
            'tinggi_badan'    => 'required|numeric|min:30|max:150',
            'lingkar_kepala'  => 'nullable|numeric|min:18|max:60',
            'asi_eksklusif'   => 'nullable',
            'status_kenaikan' => 'nullable|string|max:10',
            'catatan_kader'   => 'nullable|string|max:500',
        ];
    }

    /**
     * Validasi rentang age-aware (WHO median ± 6 SD) agar data typo (mis. TB 11,4 cm) tertolak.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $balita = Balita::find($this->input('balita_id'));
            $tanggal = $this->input('tanggal_ukur');

            if (!$balita || !$balita->tanggal_lahir || !$tanggal) {
                return;
            }

            $umurBulan = (int) Carbon::parse($balita->tanggal_lahir)
                ->diffInMonths(Carbon::parse($tanggal));

            $ref = app(GrowthCalculationService::class)->referenceFor($umurBulan, $balita->jenis_kelamin);
            if (!$ref || $ref['bb_median'] <= 0 || $ref['tb_median'] <= 0) {
                return;
            }

            $factor = 6.0; // rentang normal WHO ±6 SD (toleransi ekstrem nyata, menolak data typo)
            $bb = (float) $this->input('berat_badan');
            $tb = (float) $this->input('tinggi_badan');

            if ($bb < $ref['bb_median'] - $factor * $ref['bb_sd'] || $bb > $ref['bb_median'] + $factor * $ref['bb_sd']) {
                $validator->errors()->add('berat_badan', "Berat badan tidak sesuai rentang normal umur {$umurBulan} bulan.");
            }

            if ($tb < $ref['tb_median'] - $factor * $ref['tb_sd'] || $tb > $ref['tb_median'] + $factor * $ref['tb_sd']) {
                $validator->errors()->add('tinggi_badan', "Tinggi badan tidak sesuai rentang normal umur {$umurBulan} bulan.");
            }
        });
    }
}
