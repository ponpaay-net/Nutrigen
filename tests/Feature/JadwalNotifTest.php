<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Jadwal;
use App\Models\Kader;
use App\Models\NotificationLog;
use App\Models\OrangTua;
use App\Models\Posyandu;
use App\Models\Puskesmas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * NOTIF-01 — Kader mengirim pengingat WhatsApp ke semua Ibu balita di posyandunya.
 */
class JadwalNotifTest extends TestCase
{
    use RefreshDatabase;

    private User $kaderUser;
    private Posyandu $posyandu;
    private Jadwal $jadwal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $puskesmas = Puskesmas::create(['nama' => 'Puskesmas A', 'kode_faskes' => 'PKA' . uniqid()]);
        $this->posyandu = Posyandu::create(['puskesmas_id' => $puskesmas->id, 'nama' => 'Posyandu A', 'desa_kelurahan' => 'Desa A']);

        $this->kaderUser = User::create([
            'name' => 'Kader A', 'email' => 'kader-notif-' . uniqid() . '@test.local',
            'password' => bcrypt('password'), 'role' => 'kader',
        ]);
        Kader::create(['user_id' => $this->kaderUser->id, 'posyandu_id' => $this->posyandu->id, 'nama' => 'Kader A']);

        $this->jadwal = Jadwal::create([
            'posyandu_id' => $this->posyandu->id,
            'judul' => 'Penimbangan & Imunisasi',
            'lokasi' => 'Balai Posyandu',
            'tanggal' => now()->addDays(5)->toDateString(),
            'waktu_mulai' => '08:30', 'waktu_selesai' => '11:30',
            'catatan' => 'Bawa Buku KIA',
        ]);
    }

    public function test_kader_mengirim_notifikasi_ke_semua_ibu_dan_tercatat(): void
    {
        // Buat 3 ortu + balita di posyandu ini (semua punya WA)
        for ($i = 0; $i < 3; $i++) {
            $ortu = OrangTua::factory()->create();
            Balita::create([
                'orang_tua_id' => $ortu->id, 'posyandu_id' => $this->posyandu->id,
                'nama' => 'Anak' . $i, 'jenis_kelamin' => 'P',
                'tanggal_lahir' => now()->subMonths(20)->toDateString(),
            ]);
        }

        $this->actingAs($this->kaderUser);

        $response = $this->post(route('jadwal.notif', $this->jadwal->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Harus ada 3 log notifikasi (1 per ibu) di posyandu
        $this->assertSame(3, NotificationLog::where('orang_tua_id', '!=', null)->count());
    }

    public function test_kader_tidak_bisa_notif_jadwal_posyandu_lain(): void
    {
        $puskesmas2 = Puskesmas::create(['nama' => 'Puskesmas B', 'kode_faskes' => 'PKB' . uniqid()]);
        $posyandu2 = Posyandu::create(['puskesmas_id' => $puskesmas2->id, 'nama' => 'Posyandu B', 'desa_kelurahan' => 'Desa B']);
        $other = Jadwal::create([
            'posyandu_id' => $posyandu2->id, 'judul' => 'Jadwal Lain',
            'lokasi' => 'X', 'tanggal' => now()->addDays(3)->toDateString(),
            'waktu_mulai' => '09:00', 'waktu_selesai' => '12:00',
        ]);

        $this->actingAs($this->kaderUser);
        // findOrFail discoped ke posyandu kader -> 404
        $this->post(route('jadwal.notif', $other->id))->assertNotFound();
    }
}
