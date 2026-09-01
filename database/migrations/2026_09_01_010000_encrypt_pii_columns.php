<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up()
    {
        // 0. Hapus index UNIQUE pada balitas.nik DULU — MySQL menolak membuat kolom bertipe TEXT
        //    selama masih jadi bagian key/index. Enkripsi memakai IV acak -> tidak layak unik.
        $this->trySql("ALTER TABLE balitas DROP INDEX balitas_nik_unique");

        // 1. Lebarkan kolom PII ke TEXT (hasil enkripsi jauh lebih panjang dari varchar(16)).
        $this->trySql("ALTER TABLE balitas MODIFY nik TEXT NULL");
        $this->trySql("ALTER TABLE balitas MODIFY no_bpjs TEXT NULL");
        $this->trySql("ALTER TABLE orang_tuas MODIFY no_kk TEXT NULL");
        $this->trySql("ALTER TABLE orang_tuas MODIFY nik_ayah TEXT NULL");
        $this->trySql("ALTER TABLE orang_tuas MODIFY nik_ibu TEXT NULL");

        // 2. Re-encrypt data existing (raw DB agar tidak salah ter-cast, lalu tulis ciphertext). Idempoten.
        $this->encryptExisting();
    }

    private function trySql(string $sql): void
    {
        try { DB::statement($sql); } catch (\Throwable $e) { /* kolom/index sudah sesuai — abaikan */ }
    }

    private function encryptExisting(): void
    {
        if (! Schema::hasTable('balitas')) return;
        foreach (DB::table('balitas')->select('id', 'nik', 'no_bpjs')->get() as $r) {
            $upd = [];
            if ($r->nik && ! $this->isEncrypted($r->nik))    $upd['nik'] = Crypt::encryptString($r->nik);
            if ($r->no_bpjs && ! $this->isEncrypted($r->no_bpjs)) $upd['no_bpjs'] = Crypt::encryptString($r->no_bpjs);
            if ($upd) DB::table('balitas')->where('id', $r->id)->update($upd);
        }

        if (! Schema::hasTable('orang_tuas')) return;
        foreach (DB::table('orang_tuas')->select('id', 'no_kk', 'nik_ayah', 'nik_ibu')->get() as $r) {
            $upd = [];
            if ($r->no_kk && ! $this->isEncrypted($r->no_kk))       $upd['no_kk'] = Crypt::encryptString($r->no_kk);
            if ($r->nik_ayah && ! $this->isEncrypted($r->nik_ayah)) $upd['nik_ayah'] = Crypt::encryptString($r->nik_ayah);
            if ($r->nik_ibu && ! $this->isEncrypted($r->nik_ibu))   $upd['nik_ibu'] = Crypt::encryptString($r->nik_ibu);
            if ($upd) DB::table('orang_tuas')->where('id', $r->id)->update($upd);
        }
    }

    private function isEncrypted(string $value): bool
    {
        try { Crypt::decryptString($value); return true; } catch (\Throwable $e) { return false; }
    }

    public function down()
    {
        // Pembalikan opsional — tidak diimplementasikan agar aman.
    }
};
