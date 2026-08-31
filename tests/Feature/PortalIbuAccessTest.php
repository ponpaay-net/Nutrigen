<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Kader;
use App\Models\OrangTua;
use App\Models\Pengukuran;
use App\Models\Puskesmas;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * KRITIS-04 — Isolasi data antar keluarga di Portal Ibu.
 *
 * Aturan main: URL Portal Ibu hanya boleh menampilkan balita yang
 * benar-benar milik orang_tua yang terikat pada signature.
 */
class PortalIbuAccessTest extends TestCase
{
    use RefreshDatabase;

    private OrangTua $ortuA;
    private OrangTua $ortuB;
    private Balita $balitaA;
    private Balita $balitaB;

    protected function setUp(): void
    {
        parent::setUp();

        // Rantai relasi minimal: puskesmas -> posyandu -> (user kader) -> kader
        $puskesmas = Puskesmas::create([
            'nama' => 'Puskesmas Test',
            'kode_faskes' => 'P' . uniqid(),
        ]);

        $posyandu = Posyandu::create([
            'puskesmas_id' => $puskesmas->id,
            'nama' => 'Posyandu Test',
            'desa_kelurahan' => 'Desa Test',
        ]);

        $kaderUserA = User::create([
            'name' => 'Kader A',
            'email' => 'kader-a-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role' => 'kader',
        ]);
        $kaderUserB = User::create([
            'name' => 'Kader B',
            'email' => 'kader-b-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role' => 'kader',
        ]);

        $kaderA = Kader::create([
            'user_id' => $kaderUserA->id,
            'posyandu_id' => $posyandu->id,
            'nama' => 'Kader A',
        ]);
        $kaderB = Kader::create([
            'user_id' => $kaderUserB->id,
            'posyandu_id' => $posyandu->id,
            'nama' => 'Kader B',
        ]);

        $this->ortuA = OrangTua::factory()->create();
        $this->ortuB = OrangTua::factory()->create();

        $this->balitaA = Balita::create([
            'orang_tua_id' => $this->ortuA->id,
            'posyandu_id' => $posyandu->id,
            'nama' => 'AnakA-' . uniqid(),
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(12)->toDateString(),
        ]);

        $this->balitaB = Balita::create([
            'orang_tua_id' => $this->ortuB->id,
            'posyandu_id' => $posyandu->id,
            'nama' => 'AnakB-' . uniqid(),
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => now()->subMonths(10)->toDateString(),
        ]);

        Pengukuran::create([
            'balita_id' => $this->balitaA->id,
            'kader_id' => $kaderA->id,
            'tanggal_ukur' => now()->subDays(7)->toDateString(),
            'umur_bulan' => 12,
            'berat_badan' => 9.5,
            'tinggi_badan' => 75.0,
            'z_score_bbu' => 0.12,
            'z_score_tbu' => -0.34,
            'status_gizi' => 'Baik',
            'status_validasi' => 'approved',
        ]);

        Pengukuran::create([
            'balita_id' => $this->balitaB->id,
            'kader_id' => $kaderB->id,
            'tanggal_ukur' => now()->subDays(5)->toDateString(),
            'umur_bulan' => 10,
            'berat_badan' => 8.8,
            'tinggi_badan' => 72.0,
            'z_score_bbu' => -0.21,
            'z_score_tbu' => 0.05,
            'status_gizi' => 'Baik',
            'status_validasi' => 'approved',
        ]);
    }

    /** Helper: signed URL portal untuk pasangan balita+orang_tua. */
    private function signedHome(Balita $balita, int $orangTuaId): string
    {
        return URL::temporarySignedRoute('portal-ibu.home', now()->addDays(1), [
            'balita' => $balita->id,
            'orang_tua' => $orangTuaId,
        ]);
    }

    public function test_ibu_melihat_data_anaknya_sendiri(): void
    {
        $response = $this->get($this->signedHome($this->balitaA, $this->ortuA->id));

        $response->assertOk();
        $response->assertSee($this->balitaA->nama, false);
        // Data pengukuran approved miliknya tampil (cast float: 9.50 -> "9.5")
        $response->assertSee('9.5', false);
    }

    public function test_balita_milik_orang_lain_harus_kosong_meski_signature_valid(): void
    {
        // Signature VALID (dibuat utk kombinasi ini), tapi balita bukan milik ortu ini.
        // Inilah inti KRITIS-04: server wajib menolak meski signature lolos.
        $response = $this->get($this->signedHome($this->balitaB, $this->ortuA->id));

        $response->assertOk(); // halaman tetap render...
        $response->assertDontSee($this->balitaB->nama, false); // ...tapi tanpa data anak orang lain
        $response->assertDontSee('8.8', false);
    }

    public function test_mengubah_balita_id_di_url_harus_ditolak_403(): void
    {
        // Tamper: ganti balita id setelah URL ditandatangani -> signature tidak cocok
        $url = str_replace(
            "balita={$this->balitaA->id}",
            "balita={$this->balitaB->id}",
            $this->signedHome($this->balitaA, $this->ortuA->id)
        );

        $this->get($url)->assertForbidden();
    }

    public function test_tanpa_parameter_orang_tua_harus_kosong(): void
    {
        // URL lama gaya sebelum perbaikan: hanya membawa balita
        $legacy = URL::temporarySignedRoute('portal-ibu.home', now()->addDays(1), [
            'balita' => $this->balitaA->id,
        ]);

        $response = $this->get($legacy);

        $response->assertOk();
        $response->assertDontSee($this->balitaA->nama, false); // empty state, bukan data
    }

    public function test_signature_expired_harus_ditolak_403(): void
    {
        $expired = URL::temporarySignedRoute('portal-ibu.home', now()->subMinute(), [
            'balita' => $this->balitaA->id,
            'orang_tua' => $this->ortuA->id,
        ]);

        $this->get($expired)->assertForbidden();
    }

    public function test_child_selector_hanya_menampilkan_anak_kandung(): void
    {
        $url = URL::temporarySignedRoute('portal-ibu.child-selector', now()->addDays(1), [
            'orang_tua' => $this->ortuA->id,
        ]);

        $response = $this->get($url);

        $response->assertOk();
        $response->assertSee($this->balitaA->nama, false);
        $response->assertDontSee($this->balitaB->nama, false);
    }

    public function test_pengukuran_pending_tidak_tampil_di_portal(): void
    {
        // Tambah pengukuran pending yang lebih baru utk balita B
        $kader = Kader::where('nama', 'Kader B')->firstOrFail();
        Pengukuran::create([
            'balita_id' => $this->balitaB->id,
            'kader_id' => $kader->id,
            'tanggal_ukur' => now()->toDateString(),
            'umur_bulan' => 10,
            'berat_badan' => 9.9,
            'tinggi_badan' => 73.0,
            'z_score_bbu' => -1.02,
            'z_score_tbu' => -0.15,
            'status_gizi' => 'Baik',
            'status_validasi' => 'pending',
        ]);

        $response = $this->get($this->signedHome($this->balitaB, $this->ortuB->id));

        $response->assertOk();
        $response->assertSee($this->balitaB->nama, false);
        $response->assertDontSee('9.90', false); // angka ukur pending tak boleh bocor
    }
}
