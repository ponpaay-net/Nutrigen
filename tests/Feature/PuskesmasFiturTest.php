<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Kader;
use App\Models\OrangTua;
use App\Models\Pengukuran;
use App\Models\Posyandu;
use App\Models\Puskesmas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifikasi menyeluruh semua halaman & fitur Portal Puskesmas bisa di-render
 * dan tidak menghasilkan HTTP 500 (bug sistem/variabel/layout).
 */
class PuskesmasFiturTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Puskesmas $puskesmas;
    private Posyandu $posyandu;
    private Balita $balita;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->user = User::create([
            'name' => 'Petugas Puskesmas',
            'email' => 'puskesmas-fitur-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role' => 'puskesmas',
            'email_verified_at' => now(),
        ]);

        $this->puskesmas = Puskesmas::create([
            'user_id' => $this->user->id,
            'nama' => 'Puskesmas Fitur',
            'kode_faskes' => 'PKF' . uniqid(),
        ]);

        $this->posyandu = Posyandu::create([
            'puskesmas_id' => $this->puskesmas->id,
            'nama' => 'Posyandu Fitur',
            'desa_kelurahan' => 'Desa Fitur',
        ]);

        $kaderUser = User::create([
            'name' => 'Kader Fitur', 'email' => 'kader-fitur-' . uniqid() . '@test.local',
            'password' => bcrypt('password'), 'role' => 'kader',
        ]);
        Kader::create(['user_id' => $kaderUser->id, 'posyandu_id' => $this->posyandu->id, 'nama' => 'Kader Fitur']);

        $ortu = OrangTua::factory()->create();
        $this->balita = Balita::create([
            'orang_tua_id' => $ortu->id,
            'posyandu_id' => $this->posyandu->id,
            'nama' => 'Anak Fitur', 'nik' => '1234567890123456',
            'jenis_kelamin' => 'L', 'tanggal_lahir' => now()->subMonths(12)->toDateString(),
        ]);

        Pengukuran::create([
            'balita_id' => $this->balita->id,
            'kader_id' => Kader::where('posyandu_id', $this->posyandu->id)->first()->id,
            'tanggal_ukur' => now()->toDateString(),
            'umur_bulan' => 12,
            'berat_badan' => 9.5, 'tinggi_badan' => 75.0, 'lingkar_kepala' => 43.0,
            'z_score_bbu' => -0.5, 'z_score_tbu' => -0.3, 'z_score_bbt' => -0.2,
            'status_gizi' => 'Normal', 'status_validasi' => 'pending',
        ]);

        $this->actingAs($this->user);
    }

    public function test_dashboard_bisa_di_render(): void
    {
        $this->get(route('puskesmas.dashboard'))->assertStatus(200);
    }

    public function test_daftar_balita_dan_filter_bisa_di_render(): void
    {
        $this->get(route('puskesmas.balita'))->assertStatus(200);
        $this->get(route('puskesmas.balita', ['q' => 'Anak']))->assertStatus(200);
        $this->get(route('puskesmas.balita', ['status_gizi' => 'normal']))->assertStatus(200);
    }

    public function test_detail_balita_bisa_di_render(): void
    {
        $this->get(route('puskesmas.balita.show', $this->balita->id))->assertStatus(200);
    }

    public function test_antrean_validasi_tab_bisa_di_render(): void
    {
        $this->get(route('puskesmas.validasi'))->assertStatus(200);
        $this->get(route('puskesmas.validasi', ['tab' => 'normal']))->assertStatus(200);
        $this->get(route('puskesmas.validasi', ['tab' => 'selesai']))->assertStatus(200);
    }

    public function test_review_validasi_bisa_di_render(): void
    {
        $p = Pengukuran::first();
        $this->get(route('puskesmas.validasi.review', $p->id))->assertStatus(200);
    }

    public function test_riwayat_validasi_bisa_di_render(): void
    {
        $p = Pengukuran::first();
        $this->get(route('puskesmas.validasi.riwayat', $p->id))->assertStatus(200);
    }

    public function test_laporan_dan_filter_bisa_di_render(): void
    {
        $this->get(route('puskesmas.laporan'))->assertStatus(200);
        $this->get(route('puskesmas.laporan', ['bulan' => now()->format('m'), 'tahun' => now()->format('Y')]))->assertStatus(200);
    }

    public function test_export_csv_dan_pdf_bisa_di_generate(): void
    {
        $this->get(route('puskesmas.laporan.export.csv'))->assertStatus(200);
        $this->get(route('puskesmas.laporan.cetak.pdf'))->assertStatus(200);
    }

    public function test_halaman_posyandu_dan_pengaturan_bisa_di_render(): void
    {
        $this->get(route('puskesmas.posyandu'))->assertStatus(200);
        $this->get(route('puskesmas.pengaturan'))->assertStatus(200);
        $this->get(route('puskesmas.pengaturan.petugas'))->assertStatus(200);
        $this->get(route('puskesmas.pengaturan.keamanan'))->assertStatus(200);
        $this->get(route('puskesmas.pengaturan.notifikasi'))->assertStatus(200);
    }

    public function test_approve_dan_reject_validasi_berfungsi(): void
    {
        $p = Pengukuran::first();

        $this->post(route('puskesmas.validasi.approve', $p->id), ['catatan_validator' => 'Data lengkap'])->assertRedirect();
        $this->assertSame('approved', $p->fresh()->status_validasi);

        $p2 = Pengukuran::create([
            'balita_id' => $this->balita->id,
            'kader_id' => Kader::where('posyandu_id', $this->posyandu->id)->first()->id,
            'tanggal_ukur' => now()->toDateString(), 'umur_bulan' => 12,
            'berat_badan' => 9.5, 'tinggi_badan' => 75.0, 'status_gizi' => 'Normal', 'status_validasi' => 'pending',
        ]);
        $this->post(route('puskesmas.validasi.reject', $p2->id), ['catatan_validator' => 'Anomali'])->assertRedirect();
        $this->assertSame('rejected', $p2->fresh()->status_validasi);
    }
}