<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Balita;
use App\Models\Pengukuran;
use App\Policies\BalitaPolicy;
use App\Services\StatisticsService;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // SEDANG-04 — otorisasi terpusat akses balita.
        Gate::policy(Balita::class, BalitaPolicy::class);

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // Share notification data for Kader and Puskesmas navigation.
        View::composer(['components.navbar', 'components.puskesmas-footer', 'layouts.app', 'layouts.puskesmas'], function ($view) {
            $user = Auth::user();
            $revisiList = collect();
            $revisiCount = 0;
            $validationNotifs = collect();
            $validationNotifsCount = 0;

            if ($user && $user->role === 'kader') {
                $posyanduId = $user->kader?->posyandu_id;
                if ($posyanduId) {
                    // Query balitas whose LATEST measurement is currently 'rejected'
                    // When kader remeasures/updates the child, the latest status becomes 'pending', so it automatically disappears from notifications.
                    $balitas = \App\Models\Balita::where('posyandu_id', $posyanduId)
                        ->with(['latestPengukuran'])
                        ->get()
                        ->filter(function ($b) {
                            return $b->latestPengukuran && $b->latestPengukuran->status_validasi === 'rejected';
                        });

                    $revisiList = $balitas->map(function ($b) {
                        $p = $b->latestPengukuran;
                        $tgl = $p->tanggal_ukur ? Carbon::parse($p->tanggal_ukur)->translatedFormat('d M Y') : '-';
                        return [
                            'id' => $p->id,
                            'balita_id' => $b->id,
                            'balita_nama' => $b->nama ?? 'Balita',
                            'balita_nik' => $b->nik ?? '-',
                            'tanggal' => $tgl,
                            'bb' => $p->berat_badan ? number_format($p->berat_badan, 1, ',', '.') : '-',
                            'tb' => $p->tinggi_badan ? number_format($p->tinggi_badan, 1, ',', '.') : '-',
                            'catatan' => $p->catatan_validator ?: 'Data pengukuran perlu diperbaiki atau ditimbang ulang oleh kader sesuai arahan Puskesmas.',
                            'updated_diff' => $p->updated_at ? $p->updated_at->diffForHumans() : '',
                        ];
                    })->values();

                    $revisiCount = $revisiList->count();
                }
            }

            if ($user && $user->role === 'puskesmas' && $user->puskesmas?->id) {
                $validationNotifs = Pengukuran::with(['balita', 'kader'])
                    ->whereHas('balita.posyandu', function ($query) use ($user) {
                        $query->where('puskesmas_id', $user->puskesmas->id);
                    })
                    ->where('status_validasi', 'pending')
                    ->orderByDesc('tanggal_ukur')
                    ->take(10)
                    ->get()
                    ->map(function ($measurement) {
                        return [
                            'id' => $measurement->id,
                            'balita_id' => $measurement->balita_id,
                            'balita_nama' => $measurement->balita->nama ?? 'Balita',
                            'kader_nama' => $measurement->kader->nama ?? 'Kader',
                            'tanggal' => $measurement->tanggal_ukur
                                ? Carbon::parse($measurement->tanggal_ukur)->translatedFormat('d M Y')
                                : '-',
                            'bb' => number_format((float) $measurement->berat_badan, 1, ',', '.'),
                            'tb' => number_format((float) $measurement->tinggi_badan, 1, ',', '.'),
                        ];
                    });

                $validationNotifsCount = Pengukuran::whereHas('balita.posyandu', function ($query) use ($user) {
                    $query->where('puskesmas_id', $user->puskesmas->id);
                })->where('status_validasi', 'pending')->count();
            }

            $view->with([
                'revisiNotifs' => $revisiList,
                'revisiNotifsCount' => $revisiCount,
                'validationNotifs' => $validationNotifs,
                'validationNotifsCount' => $validationNotifsCount,
                'notificationRole' => $user?->role,
            ]);
        });

        View::composer('components.puskesmas-sidebar', function ($view) {
            $user = Auth::user();
            $pendingValidationCount = 0;

            if ($user && $user->role === 'puskesmas' && $user->puskesmas?->id) {
                $pendingValidationCount = app(StatisticsService::class)
                    ->getValidationQueueStats($user->puskesmas->id)['pending'];
            }

            $view->with('pendingValidationCount', $pendingValidationCount);
        });
    }
}
