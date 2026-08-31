<?php

namespace App\Services;

use App\Models\Balita;
use App\Models\Pengukuran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * DashboardService
 * 
 * Analytics Layer untuk mengekstraksi statistik pertumbuhan balita.
 * Dioptimasi dengan Query Builder / Aggregation untuk mencegah masalah N+1
 * pada Dashboard Puskesmas maupun Dashboard Kader.
 */
class DashboardService
{
    /**
     * Mengambil statistik komprehensif untuk Dashboard Puskesmas.
     *
     * @param int $puskesmasId
     * @return array{total_balita: int, normal: int, risiko: int, stunting: int, bulan_ini: int}
     */
    public function getPuskesmasDashboardStats(int $puskesmasId, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $totalBalita = Balita::whereHas('posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->count();

        $distribution = $this->getGrowthDistribution('puskesmas_id', $puskesmasId, $month, $year);

        $pengukuranBulanIni = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->whereMonth('tanggal_ukur', $month)
          ->whereYear('tanggal_ukur', $year)
          ->distinct('balita_id')
          ->count('balita_id');

        return [
            'total_balita' => $totalBalita,
            'normal'       => $distribution['Normal'] ?? 0,
            'risiko'       => $distribution['Risiko'] ?? 0,
            'stunting'     => $distribution['Stunting'] ?? 0,
            'bulan_ini'    => $pengukuranBulanIni,
        ];
    }

    /**
     * Mengambil statistik komprehensif untuk Dashboard Kader (level Posyandu).
     *
     * @param int $posyanduId
     * @return array{total_balita: int, normal: int, risiko: int, stunting: int, bulan_ini: int}
     */
    public function getKaderDashboardStats(int $posyanduId, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $totalBalita = Balita::where('posyandu_id', $posyanduId)->count();
        
        $distribution = $this->getGrowthDistribution('posyandu_id', $posyanduId, $month, $year);

        $pengukuranBulanIni = Pengukuran::whereHas('balita', function ($q) use ($posyanduId) {
            $q->where('posyandu_id', $posyanduId);
        })->whereMonth('tanggal_ukur', $month)
          ->whereYear('tanggal_ukur', $year)
          ->distinct('balita_id')
          ->count('balita_id');

        return [
            'total_balita' => $totalBalita,
            'normal'       => $distribution['Normal'] ?? 0,
            'risiko'       => $distribution['Risiko'] ?? 0,
            'stunting'     => $distribution['Stunting'] ?? 0,
            'bulan_ini'    => $pengukuranBulanIni,
        ];
    }

    /**
     * Mengambil distribusi status gizi (Normal, Risiko, Stunting) pada bulan ini.
     * Menggunakan query aggregate level DB untuk kecepatan tinggi (O(1) memory).
     *
     * @param string $scope 'puskesmas_id' atau 'posyandu_id'
     * @param int $scopeId
     * @return array<string, int> Mapping status ke jumlah balita
     */
    public function getGrowthDistribution(string $scope, int $scopeId, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $query = DB::table('pengukurans')
            ->join('balitas', 'balitas.id', '=', 'pengukurans.balita_id');
            
        if ($scope === 'puskesmas_id') {
            $query->join('posyandus', 'posyandus.id', '=', 'balitas.posyandu_id')
                  ->where('posyandus.puskesmas_id', $scopeId);
        } else {
            $query->where('balitas.posyandu_id', $scopeId);
        }

        return $query->whereMonth('pengukurans.tanggal_ukur', $month)
            ->whereYear('pengukurans.tanggal_ukur', $year)
            ->select('pengukurans.status_gizi', DB::raw('count(*) as total'))
            ->groupBy('pengukurans.status_gizi')
            ->pluck('total', 'status_gizi')
            ->toArray();
    }

    /**
     * Mengambil rekap jumlah pengukuran bulanan selama 6 bulan terakhir.
     * Sangat berguna untuk diumpankan ke Chart.js pada Frontend.
     *
     * @param int $puskesmasId
     * @return array<string, int> Mapping bulan (YYYY-MM) ke jumlah pengukuran
     */
    public function getMonthlyMeasurementSummary(int $puskesmasId): array
    {
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        
        return DB::table('pengukurans')
            ->join('balitas', 'balitas.id', '=', 'pengukurans.balita_id')
            ->join('posyandus', 'posyandus.id', '=', 'balitas.posyandu_id')
            ->where('posyandus.puskesmas_id', $puskesmasId)
            ->where('pengukurans.tanggal_ukur', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('DATE_FORMAT(pengukurans.tanggal_ukur, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('total', 'month')
            ->toArray();
    }

    /**
     * Mendapatkan Top 5 Posyandu dengan Kasus Risiko Tertinggi.
     */
    public function getTopBerisiko(int $puskesmasId, $posyanduId, int $month, int $year): array
    {
        $query = DB::table('pengukurans')
            ->join('balitas', 'balitas.id', '=', 'pengukurans.balita_id')
            ->join('posyandus', 'posyandus.id', '=', 'balitas.posyandu_id')
            ->where('posyandus.puskesmas_id', $puskesmasId)
            ->whereMonth('pengukurans.tanggal_ukur', $month)
            ->whereYear('pengukurans.tanggal_ukur', $year)
            ->whereIn('pengukurans.status_gizi', ['Risiko', 'Stunting', 'Kurang', 'risiko', 'stunting', 'kurang']);
            
        if ($posyanduId !== 'semua') {
            $query->where('balitas.posyandu_id', $posyanduId);
        }

        return $query->select('posyandus.nama', DB::raw('COUNT(DISTINCT pengukurans.balita_id) as total'))
            ->groupBy('posyandus.id', 'posyandus.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Mendapatkan data tren gizi 6 bulan terakhir dengan respect terhadap posyandu filter.
     */
    public function getTrend6Bulan(int $puskesmasId, $posyanduId, int $month, int $year, int $monthsCount = 6): array
    {
        $trends = [];
        for ($i = $monthsCount - 1; $i >= 0; $i--) {
            $date = Carbon::createFromDate($year, $month, 1)->subMonths($i);
            $m = $date->month;
            $y = $date->year;
            
            $query = DB::table('pengukurans')
                ->join('balitas', 'balitas.id', '=', 'pengukurans.balita_id')
                ->join('posyandus', 'posyandus.id', '=', 'balitas.posyandu_id')
                ->where('posyandus.puskesmas_id', $puskesmasId)
                ->whereMonth('pengukurans.tanggal_ukur', $m)
                ->whereYear('pengukurans.tanggal_ukur', $y);

            if ($posyanduId !== 'semua') {
                $query->where('balitas.posyandu_id', $posyanduId);
            }

            $stats = $query->select(DB::raw('LOWER(pengukurans.status_gizi) as sg'), DB::raw('count(*) as total'))
                ->groupBy('sg')
                ->pluck('total', 'sg')
                ->toArray();
            
            $normal = $stats['normal'] ?? 0;
            $risiko = ($stats['risiko'] ?? 0) + ($stats['stunting'] ?? 0) + ($stats['kurang'] ?? 0);
            $total = $normal + $risiko;
            
            $trends[] = [
                'bulan' => $date->translatedFormat('M'),
                'normal' => $normal,
                'berisiko' => $risiko,
                'total' => $total,
                'pct_normal' => $total > 0 ? round(($normal / $total) * 100) : 0,
                'pct_berisiko' => $total > 0 ? round(($risiko / $total) * 100) : 0,
            ];
        }
        return $trends;
    }
}
