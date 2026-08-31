<?php

namespace App\Policies;

use App\Models\Balita;
use App\Models\User;

/**
 * SEDANG-04 — Otorisasi terpusat untuk akses data balita.
 *
 * Merangkum aturan yang sebelumnya tersebar manual di banyak controller
 * (getKaderPosyanduId / getPuskesmasId / scoping orang_tua) ke satu tempat:
 *   - kader      : hanya balita di posyandu miliknya (via relasi kader->posyandu)
 *   - puskesmas  : hanya balita yang posyandunya berada di wilayah puskesmas tersebut
 *   - ibu/ortu   : hanya balita miliknya (orang_tua_id = user->orangTua->id)
 *   - lainnya    : ditolak
 */
class BalitaPolicy
{
    public function view(User $user, Balita $balita): bool
    {
        return $this->canAccess($user, $balita);
    }

    public function update(User $user, Balita $balita): bool
    {
        return $this->canAccess($user, $balita);
    }

    public function delete(User $user, Balita $balita): bool
    {
        return $this->canAccess($user, $balita);
    }

    protected function canAccess(User $user, Balita $balita): bool
    {
        return match ($user->role) {
            'kader'     => $user->kader?->posyandu_id === $balita->posyandu_id,
            'puskesmas' => $balita->posyandu?->puskesmas_id === $user->puskesmas?->id,
            'ibu'       => $user->orangTua?->id === $balita->orang_tua_id,
            default     => false,
        };
    }
}
