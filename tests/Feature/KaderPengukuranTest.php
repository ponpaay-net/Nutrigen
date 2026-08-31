<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Kader;
use App\Models\OrangTua;
use App\Models\Pengukuran;
use App\Models\Puskesmas;
use App\Models\Posyandu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEDANG-02 — Alur inti "kader menyimpan pengukuran".
 *
 * Simpan pengukuran harus menghasilkan baris pengukurans baru dengan
 * status_validasi 'pending', status gizi terhitung, dan kader hanya boleh
 * mengukur balita di posyandunya sendiri.
 */
class KaderPengukuranTest extends TestCase
{
    use RefreshDatabase;

    private User $kaderUser;
    private Kader $kader;
    private Posyandu $posyandu;
    private Balita $balita;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $puskesmas = Puskesmas::create([
            'nama'         => 'Puskesmas A',
            'kode_faskes'  => 'PKA' . uniqid(),
        ]);

        $this->posyandu = Posyandu::create([
            'puskesmas_id'   => $puskesmas->id,
            'nama'           => 'Posyandu A',
            'desa_kelurahan' => 'Desa A',
        ]);

        $this->kaderUser = User::create([
            'name'     => 'Kader A',
            'email'    => 'kader-a-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role'     => 'kader',
        ]);

        $this->kader = Kader::create([
            'user_id'       => $this->kaderUser->id,
            'posyandu_id'   => $this->posyandu->id,
            'nama'          => 'Kader A',
        ]);

        $ortu = OrangTua::factory()->create();

        // Anak lahir tepat 12 bulan lalu -> saat diukur hari ini umur = 12 bulan.
        $this->balita = Balita::create([
            'orang_tua_id'   => $ortu->id,
            'posyandu_id'    => $this->posyandu->id,
            'nama'           => 'AnakA-' . uniqid(),
            'jenis_kelamin'  => 'L',
            'tanggal_lahir'  => now()->subMonths(12)->toDateString(),
        ]);
    }

    public function test_kader_dapat_menyimpan_pengukuran_dengan_status_pending(): void
    {
        $this->actingAs($this->kaderUser);

        $response = $this->post(route('pengukuran.store'), [
            'balita_id'       => $this->balita->id,
            'tanggal_ukur'    => now()->toDateString(),
            'berat_badan'     => '9.5',
            'tinggi_badan'    => '75.0',
            'lingkar_kepala'  => '43.5',
            'asi_eksklusif'   => '1',
            'status_kenaikan' => 'naik',
            'catatan_kader'   => 'Tumbuh baik',
        ]);

        // Redirect kembali ke profil balita + flash sukses
        $response->assertRedirect(route('balita.show', $this->balita->id));
        $response->assertSessionHas('success');

        // Baris pengukurans tersimpan, status masih 'pending' (belum divalidasi puskesmas)
        $this->assertDatabaseHas('pengukurans', [
            'balita_id'       => $this->balita->id,
            'kader_id'        => $this->kader->id,
            'status_validasi' => 'pending',
            'status_gizi'     => 'Normal',
        ]);

        // Perhitungan pertumbuhan terisi (z-score & umur) oleh GrowthCalculationService
        $pengukuran = Pengukuran::where('balita_id', $this->balita->id)->firstOrFail();
        $this->assertNotNull($pengukuran->z_score_bbu);
        $this->assertNotNull($pengukuran->z_score_tbu);
        $expectedUmur = Carbon::parse($this->balita->tanggal_lahir)
            ->diffInMonths(Carbon::parse($pengukuran->tanggal_ukur->toDateString()));
        $this->assertSame($expectedUmur, $pengukuran->umur_bulan);
        $this->assertTrue($pengukuran->umur_bulan > 0);
    }

    public function test_kader_tidak_bisa_mengukur_balita_di_posyandu_lain(): void
    {
        // Posyandu lain (bukan wilayah kader yang login)
        $puskesmas2 = Puskesmas::create([
            'nama'        => 'Puskesmas B',
            'kode_faskes' => 'PKB' . uniqid(),
        ]);
        $posyandu2 = Posyandu::create([
            'puskesmas_id'   => $puskesmas2->id,
            'nama'           => 'Posyandu B',
            'desa_kelurahan' => 'Desa B',
        ]);
        $ortu2    = OrangTua::factory()->create();
        $balita2  = Balita::create([
            'orang_tua_id'  => $ortu2->id,
            'posyandu_id'   => $posyandu2->id,
            'nama'          => 'AnakB-' . uniqid(),
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => now()->subMonths(10)->toDateString(),
        ]);

        $this->actingAs($this->kaderUser);

        $response = $this->post(route('pengukuran.store'), [
            'balita_id'    => $balita2->id,
            'tanggal_ukur' => now()->toDateString(),
            'berat_badan'  => '8.8',
            'tinggi_badan' => '72.0',
        ]);

        // findOrFail yang discope ke posyandu kader -> 404, tidak ada data tersimpan
        $response->assertNotFound();
        $this->assertDatabaseMissing('pengukurans', ['balita_id' => $balita2->id]);
    }

    public function test_validasi_form_tolak_saat_field_wajib_kosong(): void
    {
        $this->actingAs($this->kaderUser);

        $response = $this->from(route('pengukuran.create'))
            ->post(route('pengukuran.store'), [
                'balita_id' => $this->balita->id,
                // tanggal_ukur, berat_badan, tinggi_badan sengaja kosong
            ]);

        $response->assertSessionHasErrors(['tanggal_ukur', 'berat_badan', 'tinggi_badan']);
        $this->assertDatabaseMissing('pengukurans', ['balita_id' => $this->balita->id]);
    }
}
