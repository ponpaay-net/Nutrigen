<?php

namespace App\Services;

/**
 * RecommendationService
 * 
 * Rule Engine untuk menghasilkan actionable recommendations berdasarkan 
 * output dari GrowthCalculationService.
 * Didesain sebagai Pure Service (SRP) tanpa query DB.
 */
class RecommendationService
{
    /**
     * Menghasilkan array rekomendasi komprehensif.
     *
     * @param string $statusGizi (Normal, Risiko, Stunting)
     * @param int $umurBulan
     * @param float $zScoreBbu
     * @param float $zScoreTbu
     * @return array{status: string, badge_color: string, dietary_advice: string, follow_up_action: string, education: string}
     */
    public function generate(string $statusGizi, int $umurBulan, float $zScoreBbu, float $zScoreTbu): array
    {
        // 1. Ambil rekomendasi dasar berdasarkan Status Gizi
        $recommendation = $this->getBaseRecommendation($statusGizi);
        
        // 2. Pertajam saran gizi berdasarkan umur (Rule Tambahan)
        $recommendation['dietary_advice'] = $this->enhanceDietaryAdvice(
            $recommendation['dietary_advice'], 
            $umurBulan
        );

        return $recommendation;
    }

    /**
     * Mendapatkan rule mapping dasar (Mapping Engine).
     */
    private function getBaseRecommendation(string $statusGizi): array
    {
        return match (strtolower($statusGizi)) {
            'stunting' => [
                'status' => 'Stunting',
                'title' => 'Memerlukan Perhatian Khusus',
                'badge_color' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20',
                'dietary_advice' => 'Tingkatkan asupan protein hewani (telur, ikan, daging) dan kalori padat setiap kali makan.',
                'follow_up_action' => 'Kunjungi Puskesmas terdekat untuk pemeriksaan lebih lanjut dan mendapatkan program pendampingan gizi yang tepat.',
                'education' => 'Pengukuran menunjukkan tinggi badan anak berada di bawah standar kurva pertumbuhan WHO untuk usianya. Penanganan sejak dini akan sangat membantu perkembangannya.',
            ],
            'risiko' => [
                'status' => 'Risiko Stunting',
                'title' => 'Perlu Pemantauan Ekstra',
                'badge_color' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
                'dietary_advice' => 'Perbaiki pola makan dengan menambahkan porsi protein ganda dan memastikan anak makan tepat waktu.',
                'follow_up_action' => 'Fokuskan pada nutrisi harian dan pastikan Anda berkonsultasi dengan Bidan pada jadwal penimbangan Posyandu berikutnya.',
                'education' => 'Pertumbuhan anak menunjukkan sedikit perlambatan dari kurva standar WHO. Intervensi nutrisi bulan ini akan membantu mengembalikan grafik pertumbuhannya.',
            ],
            default => [ // Normal
                'status' => 'Pertumbuhan Normal',
                'title' => 'Sesuai Standar Usia',
                'badge_color' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
                'dietary_advice' => 'Lanjutkan pemberian nutrisi seimbang (Karbohidrat, Protein, Serat) sesuai porsi harian.',
                'follow_up_action' => 'Pertahankan rutinitas baik ini dan hadir pada jadwal Posyandu berikutnya untuk pemantauan berkelanjutan.',
                'education' => 'Berdasarkan standar penilaian WHO, berat dan tinggi badan anak berada pada kurva pertumbuhan yang ideal.',
            ],
        };
    }

    /**
     * Memodifikasi saran berdasarkan usia spesifik (Contextual Rules).
     */
    private function enhanceDietaryAdvice(string $baseAdvice, int $umurBulan): string
    {
        if ($umurBulan < 6) {
            return $baseAdvice . ' Pastikan HANYA memberikan ASI Eksklusif (tanpa tambahan air, susu formula, atau makanan apapun) hingga usia genap 6 bulan.';
        } 
        
        if ($umurBulan >= 6 && $umurBulan < 12) {
            return $baseAdvice . ' Terapkan pemberian MPASI dengan tekstur yang tepat (saring/lumat) mendampingi ASI.';
        }
        
        if ($umurBulan >= 12 && $umurBulan <= 24) {
            return $baseAdvice . ' Anak sudah bisa mengonsumsi makanan keluarga. Lanjutkan pemberian ASI hingga usia 2 tahun jika memungkinkan.';
        }

        return $baseAdvice;
    }
}
