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
     * Hitung z-score BB/U, TB/U, BB/TB + status gizi Kemenkes & Rekomendasi PMT.
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
        
        $zBbt = null;
        if ($beratBadan !== null && $tinggiBadan !== null && $beratBadan > 0 && $tinggiBadan > 0) {
            $zBbt = $this->bbtZscore($umur, $sex, $beratBadan, $tinggiBadan);
        }

        $statusTbu = $this->determineStatusTbu($zTbu);
        $statusBbt = $this->determineStatusBbt($zBbt);
        $statusBbu = $this->determineStatusBbu($zBbu);
        
        // Backward compatibility for old status_gizi column if needed, or simply map to general string
        $status = $this->determineStatusGizi($zTbu, $zBbu);

        $rekomendasiPmt = $this->determineRekomendasiPmt($statusBbt, $statusBbu, $statusTbu);

        return [
            'umur_bulan'      => $umurBulan,
            'z_score_bbu'     => $zBbu !== null ? round($zBbu, 2) : null,
            'z_score_tbu'     => $zTbu !== null ? round($zTbu, 2) : null,
            'z_score_bbt'     => $zBbt !== null ? round($zBbt, 2) : null,
            'status_gizi'     => $status, // legacy general status
            'status_tbu'      => $statusTbu,
            'status_bbt'      => $statusBbt,
            'status_bbu'      => $statusBbu,
            'rekomendasi_pmt' => $rekomendasiPmt,
        ];
    }

    private function umurDalamBulan(\DateTimeInterface $tanggalLahir, \DateTimeInterface $tanggalUkur): int
    {
        $diff = $tanggalLahir->diff($tanggalUkur);
        return (int) ($diff->y * 12 + $diff->m);
    }

    /**
     * Kurva referensi WHO BB/U (weight-for-age) gaya resmi WHO: 7 kurva
     * z-score (-3, -2, -1, 0/median, +1, +2, +3 SD) dari tabel LMS WHO asli
     * (WhoLmsData.php). -2SD/-3SD/+2SD/+3SD & median diambil dari kolom asli
     * WHO; -1SD/+1SD dihitung via transformasi Box-Cox LMS.
     * Dipotong ke rentang umur data ±6 bulan agar kurva terlihat menanjak.
     * Output: ['neg3'=>[[umur,kg],...], 'neg2'=>..., 'neg1'=>..., 'med'=>...,
     *          'pos1'=>..., 'pos2'=>..., 'pos3'=>...]
     */
    public function bbuWhoCurve(int $umurMinBulan, int $umurMaxBulan, string $jenisKelamin): array
    {
        $sex = strtoupper($jenisKelamin) === 'P' ? 'P' : 'L';
        $from = max(0, min($umurMinBulan, $umurMaxBulan) - 6);
        $to   = min(60, max($umurMinBulan, $umurMaxBulan) + 6);

        if (empty($this->whoData['bbu_' . $sex])) {
            return [];
        }

        $series = ['neg3' => [], 'neg2' => [], 'neg1' => [], 'med' => [], 'pos1' => [], 'pos2' => [], 'pos3' => []];
        for ($m = $from; $m <= $to; $m++) {
            $row = $this->interpolateRow('bbu_' . $sex, $m);
            if (!$row) {
                continue;
            }
            // Format baris: [L, M, S, -3SD, -2SD, median, +2SD, +3SD]
            [$L, $M, $S] = [$row[0], $row[1], $row[2]];
            $weightAtZ = function (float $z) use ($L, $M, $S): float {
                if (abs($L) < 1e-4) {
                    return $M * exp($S * $z);
                }
                return $M * pow(1 + $L * $S * $z, 1 / $L);
            };
            $series['neg3'][] = [$m, (float) $row[3]];
            $series['neg2'][] = [$m, (float) $row[4]];
            $series['neg1'][] = [$m, round($weightAtZ(-1), 2)];
            $series['med'][]  = [$m, (float) $row[5]];
            $series['pos1'][] = [$m, round($weightAtZ(1), 2)];
            $series['pos2'][] = [$m, (float) $row[6]];
            $series['pos3'][] = [$m, (float) $row[7]];
        }

        return $series;
    }

    /**
     * Z-Score IMT/U (BMI-for-age) — indeks massa tubuh per umur, standar WHO 2006.
     * BMI = berat(kg) / tinggi(m)^2. Lalu Box-Cox vs referensi imtu (bmifa).
     */
    public function imtuZscore(int $umurBulan, string $jenisKelamin, float $berat, float $tinggiCm): ?float
    {
        $sex = strtoupper($jenisKelamin) === 'P' ? 'P' : 'L';
        $umur = max(0, min(60, $umurBulan));
        if ($berat <= 0 || $tinggiCm <= 0) {
            return null;
        }
        $bmi = $berat / pow($tinggiCm / 100, 2);
        $r = $this->interpolateRow('imtu_' . $sex, $umur);
        return round($this->zScore($bmi, $r[0], $r[1], $r[2]), 2);
    }

    /**
     * Z-Score BB/TB (weight-for-height / wasting) — WHO 2006.
     * <24 bln (recumbent): wfl by length ; >=24 bln (standing): wfh by height.
     */
    public function bbtZscore(int $umurBulan, string $jenisKelamin, float $berat, float $tinggiCm): ?float
    {
        $sex = strtoupper($jenisKelamin) === 'P' ? 'P' : 'L';
        if ($berat <= 0 || $tinggiCm <= 0) {
            return null;
        }
        $set = $umurBulan < 24 ? 'wfl_' . $sex : 'wfh_' . $sex;
        $row = $this->heightInterpolate($set, $tinggiCm);
        if (!$row) {
            return null;
        }
        return round($this->zScore($berat, $row[0], $row[1], $row[2]), 2);
    }

    /**
     * Ambil baris LMS (L,M,S,...) untuk satu tinggi (cm) dengan interpolasi linear
     * antar titik WHO (resolusi 0.5 cm). $set = 'wfl_X'|'wfh_X'.
     */
    private function heightInterpolate(string $set, float $cm): ?array
    {
        if (!isset($this->whoData[$set])) {
            return null;
        }
        $rows = $this->whoData[$set];
        $keys = array_keys($rows);
        $min = min($keys);
        $max = max($keys);
        $target = (int) round($cm * 10);
        if ($target <= $min) {
            return $rows[$min];
        }
        if ($target >= $max) {
            return $rows[$max];
        }
        if (isset($rows[$target])) {
            return $rows[$target];
        }
        // cari key terdekat di bawah & di atas
        $below = $min;
        $above = $max;
        foreach ($keys as $k) {
            if ($k <= $target) {
                $below = $k;
            }
            if ($k > $target) {
                $above = $k;
                break;
            }
        }
        $b = $rows[$below];
        $a = $rows[$above];
        $ratio = ($target - $below) / ($above - $below);
        $out = [];
        for ($i = 0; $i < 8; $i++) {
            $out[$i] = $b[$i] + ($a[$i] - $b[$i]) * $ratio;
        }
        return $out;
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
     * - Stunting: TB/U < -2 SD
     * - Kurang (underweight): BB/U < -2 SD
     * - Risiko: at-risk bawah (z < -1.5) ATAU risiko gizi lebih (BB/U > +1 SD) ATAU data ekstrem (TB/U > +3 SD)
     * - Normal: sisanya
     */
    private function determineStatusGizi(?float $zTbu, ?float $zBbu): string
    {
        if ($zTbu !== null && $zTbu < -2.0) {
            return 'Stunting';
        }
        if ($zBbu !== null && $zBbu < -2.0) {
            return 'Kurang';
        }
        if (($zTbu !== null && $zTbu < -1.5) || ($zBbu !== null && $zBbu < -1.5)
            || ($zBbu !== null && $zBbu > 1.0) || ($zTbu !== null && $zTbu > 3.0)) {
            return 'Risiko'; // at-risk bawah / gizi lebih / data ekstrem
        }
        return 'Normal';
    }

    private function determineStatusTbu(?float $z): ?string
    {
        if ($z === null) return null;
        if ($z < -3.0) return 'Sangat Pendek';
        if ($z >= -3.0 && $z < -2.0) return 'Pendek';
        if ($z >= -2.0 && $z <= 3.0) return 'Normal';
        return 'Tinggi';
    }

    private function determineStatusBbt(?float $z): ?string
    {
        if ($z === null) return null;
        if ($z < -3.0) return 'Gizi Buruk';
        if ($z >= -3.0 && $z < -2.0) return 'Gizi Kurang';
        if ($z >= -2.0 && $z <= 1.0) return 'Gizi Baik';
        if ($z > 1.0 && $z <= 2.0) return 'Berisiko Gizi Lebih';
        if ($z > 2.0 && $z <= 3.0) return 'Gizi Lebih';
        return 'Obesitas';
    }

    private function determineStatusBbu(?float $z): ?string
    {
        if ($z === null) return null;
        if ($z < -3.0) return 'BB Sangat Kurang';
        if ($z >= -3.0 && $z < -2.0) return 'BB Kurang';
        if ($z >= -2.0 && $z <= 1.0) return 'Normal';
        return 'Risiko Lebih';
    }

    private function determineRekomendasiPmt(?string $statusBbt, ?string $statusBbu, ?string $statusTbu): ?string
    {
        // 1. Prioritas PMT Pemulihan (90 Hari)
        if (in_array($statusBbt, ['Gizi Kurang', 'Gizi Buruk'])) {
            return 'Prioritas PMT Pemulihan (90 Hari)';
        }
        
        // 2. PMT Pencegahan (14-28 Hari)
        if (in_array($statusBbu, ['BB Kurang', 'BB Sangat Kurang'])) {
            return 'PMT Pencegahan (14-28 Hari)';
        }

        // 3. Evaluasi Medis
        if (in_array($statusTbu, ['Pendek', 'Sangat Pendek'])) {
            return 'Evaluasi Medis Stunting (Puskesmas)';
        }

        return 'Pemantauan Rutin Posyandu';
    }
}
