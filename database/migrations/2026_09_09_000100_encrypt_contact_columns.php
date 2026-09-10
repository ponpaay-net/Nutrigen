<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Enkripsi kolom kontak (nomor HP kader & nomor telepon puskesmas) yang
 * sebelumnya tersimpan plaintext.
 *
 * Idempoten: hanya mengenkripsi nilai yang masih plaintext (bukan ciphertext
 * "eyJ..."). Aman dijalankan ulang (migrasi di-re-run hanya sekali oleh
 * Laravel, tapi tetap kami jaga idempoten untuk rollout data lama di DB
 * production yang mungkin belum ter-encrypt).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Kader: no_hp
        if (Schema::hasTable('kaders') && Schema::hasColumn('kaders', 'no_hp')) {
            foreach (DB::table('kaders')->select('id', 'no_hp')->get() as $r) {
                if ($r->no_hp !== null && !str_starts_with((string) $r->no_hp, 'eyJ')) {
                    DB::table('kaders')->where('id', $r->id)->update([
                        'no_hp' => encrypt((string) $r->no_hp, false),
                    ]);
                }
            }
        }

        // Puskesmas: no_telp
        if (Schema::hasTable('puskesmas') && Schema::hasColumn('puskesmas', 'no_telp')) {
            foreach (DB::table('puskesmas')->select('id', 'no_telp')->get() as $r) {
                if ($r->no_telp !== null && !str_starts_with((string) $r->no_telp, 'eyJ')) {
                    DB::table('puskesmas')->where('id', $r->id)->update([
                        'no_telp' => encrypt((string) $r->no_telp, false),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Tidak perlu memutar balik enkripsi — data tetap terbaca via model.
    }
};