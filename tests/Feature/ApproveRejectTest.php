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
use Tests\TestCase;

/**
 * SEDANG-02 + TINGGI-03 — Alur inti "puskesmas approve / reject pengukuran".
 *
 * Approve/reject harus mengubah status, dan (sesuai TINGGI-03) wajib mencatat
 * siapa validator (validated_by) dan kapan (validated_at). Puskesmas hanya
 * boleh memproses pengukuran di wilayahnya sendiri.
 */
class ApproveRejectTest extends TestCase
{
    use RefreshDatabase;

    private User $puskesmasUser;
    private Puskesmas $puskesmas;
    private Pengukuran $pengukuran;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->puskesmasUser = User::create([
            'name'     => 'Puskesmas A',
            'email'    => 'puskesmas-a-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role'     => 'puskesmas',
        ]);

        $this->puskesmas = Puskesmas::create([
            'user_id'     => $this->puskesmasUser->id,
            'nama'        => 'Puskesmas A',
            'kode_faskes' => 'PKA' . uniqid(),
        ]);

        $posyandu = Posyandu::create([
            'puskesmas_id'   => $this->puskesmas->id,
            'nama'           => 'Posyandu A',
            'desa_kelurahan' => 'Desa A',
        ]);

        $kaderUser = User::create([
            'name'     => 'Kader A',
            'email'    => 'kader-a-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role'     => 'kader',
        ]);
        $kader = Kader::create([
            'user_id'     => $kaderUser->id,
            'posyandu_id' => $posyandu->id,
            'nama'        => 'Kader A',
        ]);

        $ortu = OrangTua::factory()->create();
        $balita = Balita::create([
            'orang_tua_id'  => $ortu->id,
            'posyandu_id'   => $posyandu->id,
            'nama'          => 'AnakA-' . uniqid(),
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(12)->toDateString(),
        ]);

        // Satu pengukuran yang menunggu validasi (pending)
        $this->pengukuran = Pengukuran::create([
            'balita_id'       => $balita->id,
            'kader_id'        => $kader->id,
            'tanggal_ukur'    => now()->toDateString(),
            'umur_bulan'      => 12,
            'berat_badan'     => 9.5,
            'tinggi_badan'    => 75.0,
            'z_score_bbu'     => -0.1,
            'z_score_tbu'     => -0.25,
            'status_gizi'     => 'Normal',
            'status_validasi' => 'pending',
        ]);
    }

    public function test_puskesmas_approve_mengubah_status_dan_mencatat_validator(): void
    {
        $this->actingAs($this->puskesmasUser);

        $response = $this->post(route('puskesmas.validasi.approve', $this->pengukuran->id), [
            'catatan_validator' => 'Data lengkap, disetujui.',
        ]);

        $response->assertRedirect(route('puskesmas.validasi'));

        $this->pengukuran->refresh();
        $this->assertSame('approved', $this->pengukuran->status_validasi);
        $this->assertSame($this->puskesmasUser->id, $this->pengukuran->validated_by);
        $this->assertNotNull($this->pengukuran->validated_at);
        $this->assertSame('Data lengkap, disetujui.', $this->pengukuran->catatan_validator);

        // KRITIS-02: approve juga harus menghasilkan portal_link di session
        $response->assertSessionHas('portal_link');
    }

    public function test_approve_mencatat_log_notifikasi_ke_table(): void
    {
        $this->actingAs($this->puskesmasUser);

        $this->post(route('puskesmas.validasi.approve', $this->pengukuran->id), [
            'catatan_validator' => 'Data lengkap, disetujui.',
        ]);

        // TINGGI-02: setiap approve mencatat satu baris notification_logs,
        // terhubung ke orang_tua dan pengukuran dari balita tersebut.
        $this->assertDatabaseHas('notification_logs', [
            'orang_tua_id'   => $this->pengukuran->balita->orang_tua_id,
            'pengukuran_id'  => $this->pengukuran->id,
            'channel'        => 'whatsapp',
            'status'         => 'sent',
        ]);
    }

    public function test_puskesmas_reject_mengubah_status_dan_mencatat_validator(): void
    {
        $this->actingAs($this->puskesmasUser);

        $response = $this->post(route('puskesmas.validasi.reject', $this->pengukuran->id), [
            'catatan_validator' => 'Data ragu, mohon diperiksa kembali.',
        ]);

        $response->assertRedirect(route('puskesmas.validasi'));

        $this->pengukuran->refresh();
        $this->assertSame('rejected', $this->pengukuran->status_validasi);
        $this->assertSame($this->puskesmasUser->id, $this->pengukuran->validated_by);
        $this->assertNotNull($this->pengukuran->validated_at);
        $this->assertSame('Data ragu, mohon diperiksa kembali.', $this->pengukuran->catatan_validator);
    }

    public function test_puskesmas_tidak_bisa_approve_pengukuran_di_wilayah_lain(): void
    {
        // Puskesmas B yang berbeda wilayah
        $puskesmas2User = User::create([
            'name'     => 'Puskesmas B',
            'email'    => 'puskesmas-b-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role'     => 'puskesmas',
        ]);
        $puskesmas2 = Puskesmas::create([
            'user_id'     => $puskesmas2User->id,
            'nama'        => 'Puskesmas B',
            'kode_faskes' => 'PKB' . uniqid(),
        ]);
        $posyandu2 = Posyandu::create([
            'puskesmas_id'   => $puskesmas2->id,
            'nama'           => 'Posyandu B',
            'desa_kelurahan' => 'Desa B',
        ]);
        $kaderUser2 = User::create([
            'name'     => 'Kader B',
            'email'    => 'kader-b-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role'     => 'kader',
        ]);
        $kader2 = Kader::create([
            'user_id'     => $kaderUser2->id,
            'posyandu_id' => $posyandu2->id,
            'nama'        => 'Kader B',
        ]);
        $ortu2 = OrangTua::factory()->create();
        $balita2 = Balita::create([
            'orang_tua_id'  => $ortu2->id,
            'posyandu_id'   => $posyandu2->id,
            'nama'          => 'AnakB-' . uniqid(),
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => now()->subMonths(10)->toDateString(),
        ]);
        $pengukuran2 = Pengukuran::create([
            'balita_id'       => $balita2->id,
            'kader_id'        => $kader2->id,
            'tanggal_ukur'    => now()->toDateString(),
            'umur_bulan'      => 10,
            'berat_badan'     => 8.8,
            'tinggi_badan'    => 72.0,
            'z_score_bbu'     => -0.2,
            'z_score_tbu'     => 0.05,
            'status_gizi'     => 'Normal',
            'status_validasi' => 'pending',
        ]);

        // Login sebagai Puskesmas A -> mencoba approve pengukuran milik Puskesmas B
        $this->actingAs($this->puskesmasUser);

        $response = $this->post(route('puskesmas.validasi.approve', $pengukuran2->id), [
            'catatan_validator' => 'x',
        ]);

        $response->assertNotFound();

        $pengukuran2->refresh();
        $this->assertSame('pending', $pengukuran2->status_validasi);
        $this->assertNull($pengukuran2->validated_by);
        $this->assertNull($pengukuran2->validated_at);
    }
}
