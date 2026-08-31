<?php

namespace App\Services;

use DateTimeInterface;
use Carbon\Carbon;

/**
 * GrowthCalculationService
 * 
 * Core engine untuk kalkulasi pertumbuhan balita berbasis standar WHO Child Growth Standards.
 * Menggunakan Tabel Lookup Z-Score Median (M) dan Standar Deviasi (SD) riil dari WHO.
 */
class GrowthCalculationService
{
    /**
     * Menghitung umur, z-score, dan status gizi balita.
     */
    public function calculate(
        DateTimeInterface $tanggalLahir,
        DateTimeInterface $tanggalUkur,
        string $jenisKelamin,
        $beratBadan,
        $tinggiBadan
    ): array {
        $beratBadan = (float) $beratBadan;
        $tinggiBadan = (float) $tinggiBadan;

        $umurBulan = $this->calculateUmurBulan($tanggalLahir, $tanggalUkur);
        
        // Capping umur_bulan maksimal 60 (5 tahun) sesuai standar WHO balita
        $umurIndex = $umurBulan > 60 ? 60 : $umurBulan;
        
        $reference = $this->getWhoReference($umurIndex, $jenisKelamin);

        // Kalkulasi Z-Score (Nilai Aktual - Median) / Standar Deviasi
        $zScoreBbu = $this->calculateZScore($beratBadan, $reference['bb_median'], $reference['bb_sd']);
        $zScoreTbu = $this->calculateZScore($tinggiBadan, $reference['tb_median'], $reference['tb_sd']);

        // Penentuan Status Gizi Gabungan
        $statusGizi = $this->determineStatusGizi($zScoreTbu, $zScoreBbu);

        return [
            'umur_bulan'  => $umurBulan,
            'z_score_bbu' => round($zScoreBbu, 2),
            'z_score_tbu' => round($zScoreTbu, 2),
            'status_gizi' => $statusGizi,
        ];
    }

    private function calculateUmurBulan(DateTimeInterface $tanggalLahir, DateTimeInterface $tanggalUkur): int
    {
        return Carbon::parse($tanggalLahir)->diffInMonths(Carbon::parse($tanggalUkur));
    }

    private function calculateZScore(float $actualValue, float $medianValue, float $standardDeviation): float
    {
        if ($standardDeviation <= 0) {
            return 0.0; 
        }
        return ($actualValue - $medianValue) / $standardDeviation;
    }

    /**
     * Hierarki Status Gizi (WHO):
     * 1. Stunting (TB/U < -2)
     * 2. Kurang (BB/U < -2)
     * 3. Risiko (TB/U < -1.5 atau BB/U < -1.5)
     * 4. Normal
     */
    private function determineStatusGizi(float $zScoreTbu, float $zScoreBbu): string
    {
        if ($zScoreTbu < -2.0) {
            return 'Stunting';
        }
        
        if ($zScoreBbu < -2.0) {
            return 'Kurang'; 
        }
        
        if ($zScoreTbu < -1.5 || $zScoreBbu < -1.5) {
            return 'Risiko'; 
        }
        
        return 'Normal';
    }

    /**
     * Tabel Referensi WHO (0-60 Bulan)
     * Data diringkas dari WHO Child Growth Standards (Median & SD).
     */
    private function getWhoReference(int $umurBulan, string $jenisKelamin): array
    {
        $isMale = strtoupper($jenisKelamin) === 'L';

        // Laki-laki (Boys) - Median & SD
        $boys = [
            0 => ['bb_m' => 3.3, 'bb_sd' => 0.4, 'tb_m' => 49.9, 'tb_sd' => 1.9],
            1 => ['bb_m' => 4.5, 'bb_sd' => 0.5, 'tb_m' => 54.7, 'tb_sd' => 2.1],
            2 => ['bb_m' => 5.6, 'bb_sd' => 0.6, 'tb_m' => 58.4, 'tb_sd' => 2.2],
            3 => ['bb_m' => 6.4, 'bb_sd' => 0.7, 'tb_m' => 61.4, 'tb_sd' => 2.3],
            4 => ['bb_m' => 7.0, 'bb_sd' => 0.7, 'tb_m' => 63.9, 'tb_sd' => 2.4],
            5 => ['bb_m' => 7.5, 'bb_sd' => 0.8, 'tb_m' => 65.9, 'tb_sd' => 2.5],
            6 => ['bb_m' => 7.9, 'bb_sd' => 0.8, 'tb_m' => 67.6, 'tb_sd' => 2.5],
            7 => ['bb_m' => 8.3, 'bb_sd' => 0.9, 'tb_m' => 69.2, 'tb_sd' => 2.6],
            8 => ['bb_m' => 8.6, 'bb_sd' => 0.9, 'tb_m' => 70.6, 'tb_sd' => 2.6],
            9 => ['bb_m' => 8.9, 'bb_sd' => 0.9, 'tb_m' => 72.0, 'tb_sd' => 2.7],
            10 => ['bb_m' => 9.2, 'bb_sd' => 1.0, 'tb_m' => 73.3, 'tb_sd' => 2.7],
            11 => ['bb_m' => 9.4, 'bb_sd' => 1.0, 'tb_m' => 74.5, 'tb_sd' => 2.8],
            12 => ['bb_m' => 9.6, 'bb_sd' => 1.0, 'tb_m' => 75.7, 'tb_sd' => 2.8],
            18 => ['bb_m' => 10.9, 'bb_sd' => 1.2, 'tb_m' => 82.3, 'tb_sd' => 3.2],
            24 => ['bb_m' => 12.2, 'bb_sd' => 1.3, 'tb_m' => 87.1, 'tb_sd' => 3.4],
            36 => ['bb_m' => 14.3, 'bb_sd' => 1.6, 'tb_m' => 96.1, 'tb_sd' => 4.0],
            48 => ['bb_m' => 16.3, 'bb_sd' => 1.9, 'tb_m' => 103.3, 'tb_sd' => 4.4],
            60 => ['bb_m' => 18.3, 'bb_sd' => 2.2, 'tb_m' => 110.0, 'tb_sd' => 4.8],
        ];

        // Perempuan (Girls) - Median & SD
        $girls = [
            0 => ['bb_m' => 3.2, 'bb_sd' => 0.4, 'tb_m' => 49.1, 'tb_sd' => 1.8],
            1 => ['bb_m' => 4.2, 'bb_sd' => 0.5, 'tb_m' => 53.7, 'tb_sd' => 2.0],
            2 => ['bb_m' => 5.1, 'bb_sd' => 0.6, 'tb_m' => 57.1, 'tb_sd' => 2.1],
            3 => ['bb_m' => 5.8, 'bb_sd' => 0.7, 'tb_m' => 59.8, 'tb_sd' => 2.2],
            4 => ['bb_m' => 6.4, 'bb_sd' => 0.7, 'tb_m' => 62.1, 'tb_sd' => 2.3],
            5 => ['bb_m' => 6.9, 'bb_sd' => 0.8, 'tb_m' => 64.0, 'tb_sd' => 2.4],
            6 => ['bb_m' => 7.3, 'bb_sd' => 0.8, 'tb_m' => 65.7, 'tb_sd' => 2.5],
            7 => ['bb_m' => 7.6, 'bb_sd' => 0.9, 'tb_m' => 67.3, 'tb_sd' => 2.5],
            8 => ['bb_m' => 7.9, 'bb_sd' => 0.9, 'tb_m' => 68.7, 'tb_sd' => 2.6],
            9 => ['bb_m' => 8.2, 'bb_sd' => 0.9, 'tb_m' => 70.1, 'tb_sd' => 2.6],
            10 => ['bb_m' => 8.5, 'bb_sd' => 1.0, 'tb_m' => 71.5, 'tb_sd' => 2.7],
            11 => ['bb_m' => 8.7, 'bb_sd' => 1.0, 'tb_m' => 72.8, 'tb_sd' => 2.7],
            12 => ['bb_m' => 8.9, 'bb_sd' => 1.0, 'tb_m' => 74.0, 'tb_sd' => 2.8],
            18 => ['bb_m' => 10.2, 'bb_sd' => 1.2, 'tb_m' => 80.7, 'tb_sd' => 3.2],
            24 => ['bb_m' => 11.5, 'bb_sd' => 1.4, 'tb_m' => 85.7, 'tb_sd' => 3.5],
            36 => ['bb_m' => 13.9, 'bb_sd' => 1.7, 'tb_m' => 95.1, 'tb_sd' => 4.1],
            48 => ['bb_m' => 16.1, 'bb_sd' => 2.1, 'tb_m' => 102.7, 'tb_sd' => 4.6],
            60 => ['bb_m' => 18.2, 'bb_sd' => 2.5, 'tb_m' => 109.4, 'tb_sd' => 5.0],
        ];

        $dataset = $isMale ? $boys : $girls;

        if (isset($dataset[$umurBulan])) {
            return [
                'bb_median' => $dataset[$umurBulan]['bb_m'],
                'bb_sd'     => $dataset[$umurBulan]['bb_sd'],
                'tb_median' => $dataset[$umurBulan]['tb_m'],
                'tb_sd'     => $dataset[$umurBulan]['tb_sd'],
            ];
        }

        // Interpolasi Linear
        $lowerKey = 0;
        $upperKey = 60;
        foreach (array_keys($dataset) as $key) {
            if ($key < $umurBulan && $key > $lowerKey) $lowerKey = $key;
            if ($key > $umurBulan && $key < $upperKey) {
                $upperKey = $key;
                break;
            }
        }

        $lower = $dataset[$lowerKey];
        $upper = $dataset[$upperKey];
        $ratio = ($umurBulan - $lowerKey) / ($upperKey - $lowerKey);

        return [
            'bb_median' => $lower['bb_m'] + (($upper['bb_m'] - $lower['bb_m']) * $ratio),
            'bb_sd'     => $lower['bb_sd'] + (($upper['bb_sd'] - $lower['bb_sd']) * $ratio),
            'tb_median' => $lower['tb_m'] + (($upper['tb_m'] - $lower['tb_m']) * $ratio),
            'tb_sd'     => $lower['tb_sd'] + (($upper['tb_sd'] - $lower['tb_sd']) * $ratio),
        ];
    }
}
