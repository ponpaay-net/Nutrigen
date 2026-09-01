<?php

namespace App\Services;

/**
 * GrowthCalculationService — perhitungan status gizi balita 0-60 bulan.
 *
 * STANDAR: WHO Child Growth Standards 2006 (LMS) + klasifikasi BUKU KIA/WHO.
 * - Referensi L/M/S per bulan per jenis kelamin diambil dari tabel WHO asli
 *   (WhoLmsData.php, bersumber dari WHO — bukan estimasi).
 * - Z-Score memakai transformasi LMS Box-Cox:
 *      z = ((x / M)^L - 1) / (L * S)   untuk L != 0
 *      z = ln(x / M) / S               untuk L = 0
 * - Klasifikasi (cutoff WHO/Kemenkes):
 *      TB/U < -2 SD  -> Stunting ;  BB/U < -2 SD -> Kurang/Underweight
 *      TB/U atau BB/U antara -2 dan -1.5 SD -> Risiko (perlu dipantau)
 */
class GrowthCalculationService
{
    /** @var array Data WHO LMS per bulan (dari WhoLmsData.php). */
    private array $whoData;

    public function __construct()
    {
        $this->whoData = require __DIR__ . '/WhoLmsData.php';
    }

    /**
     * Hitung z-score BB/U & TB/U + status gizi.
     * Signature date-based (kompatibel dgn pemanggil: controller & seeder).
     */
    public function calculate(
        \DateTimeInterface $tanggalLahir,
        \DateTimeInterface $tanggalUkur,
        string $jenisKelamin,
        ?float $beratBadan,
        ?float $tinggiBadan
    ): array {
        $sex = strtoupper($jenisKelamin) === 'P' ? 'P' : 'L';
        $umurBulan = $this->umurDalamBulan($tanggalLahir, $tanggalUkur);
        $umur = max(0, min(60, $umurBulan));

        $ref = $this->referenceFor($umur, $sex);

        $zBbu = ($beratBadan !== null && $beratBadan > 0) ? $this->zScore($beratBadan, $ref['bb_l'], $ref['bb_median'], $ref['bb_s']) : null;
        $zTbu = ($tinggiBadan !== null && $tinggiBadan > 0) ? $this->zScore($tinggiBadan, $ref['tb_l'], $ref['tb_median'], $ref['tb_s']) : null;

        $status = $this->determineStatusGizi($zTbu, $zBbu);

        return [
            'umur_bulan'  => $umurBulan,
            'z_score_bbu' => $zBbu !== null ? round($zBbu, 2) : null,
            'z_score_tbu' => $zTbu !== null ? round($zTbu, 2) : null,
            'status_gizi' => $status,
        ];
    }

    private function umurDalamBulan(\DateTimeInterface $tanggalLahir, \DateTimeInterface $tanggalUkur): int
    {
        $diff = $tanggalLahir->diff($tanggalUkur);
        return (int) ($diff->y * 12 + $diff->m);
    }

    /**
     * Referensi WHO (L, M, S + titik SD) untuk satu umur (bulan) per jenis kelamin.
     * Untuk umur non-bulat dilakukan interpolasi linear antar bulan WHO.
     */
    public function referenceFor(int $umurBulan, string $jenisKelamin): array
    {
        $sex = strtoupper($jenisKelamin) === 'P' ? 'P' : 'L';
        $age = max(0, min(60, $umurBulan));

        $bb = $this->interpolateRow('bbu_' . $sex, $age);
        $tb = $this->interpolateRow('tbu_' . $sex, $age);

        return [
            'bb_median' => $bb[0 + 1],   // M
            'bb_sd'     => $bb[0 + 1] * $bb[2], // SD (kg) = M * S
            'bb_l'      => $bb[0],
            'bb_s'      => $bb[2],
            'bb_sd0'    => $bb[5],
            'bb_sd2n'   => $bb[3],
            'bb_sd2'    => $bb[7],
            'tb_median' => $tb[1],
            'tb_sd'     => $tb[1] * $tb[2],
            'tb_l'      => $tb[0],
            'tb_s'      => $tb[2],
            'tb_sd0'    => $tb[5],
            'tb_sd2n'   => $tb[3],
            'tb_sd2'    => $tb[7],
        ];
    }

    /**
     * Ambil satu baris LMS (L,M,S,sd2n,sd1n,sd0,sd1,sd2) untuk umur tertentu.
     */
    private function interpolateRow(string $key, float $age): array
    {
        $row = $this->whoData[$key] ?? [];
        if (!isset($row[(int) $age])) {
            // interpolasi linear antara bulan terdekat
            $below = (int) floor($age);
            $above = (int) ceil($age);
            $b = $row[$below] ?? reset($row);
            $a = $row[$above] ?? end($row);
            if ($above === $below || !isset($row[$above]) || !isset($row[$below])) {
                return $b;
            }
            $ratio = $age - $below;
            $out = [];
            for ($i = 0; $i < 8; $i++) {
                $out[$i] = $b[$i] + ($a[$i] - $b[$i]) * $ratio;
            }
            return $out;
        }
        return $row[(int) $age];
    }

    /**
     * Z-Score transformasi LMS Box-Cox: z = ((x/M)^L - 1)/(L*S), L != 0; ln(x/M)/S, L = 0.
     */
    private function zScore(float $x, float $L, float $M, float $S): float
    {
        if ($x <= 0 || $M <= 0 || $S <= 0) {
            return 0.0;
        }
        $ratio = $x / $M;
        if (abs($L) < 1e-9) {
            return log($ratio) / $S;
        }
        return (pow($ratio, $L) - 1) / ($L * $S);
    }

    /**
     * Klasifikasi status gizi (WHO / BUKU KIA - Kemenkes).
     */
    private function determineStatusGizi(?float $zTbu, ?float $zBbu): string
    {
        if ($zTbu !== null && $zTbu < -2.0) {
            return 'Stunting';
        }
        if ($zBbu !== null && $zBbu < -2.0) {
            return 'Kurang';
        }
        if (($zTbu !== null && $zTbu < -1.5) || ($zBbu !== null && $zBbu < -1.5)) {
            return 'Risiko';
        }
        return 'Normal';
    }
}
