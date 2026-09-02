<?php

namespace Tests\Feature;

use App\Models\Jadwal;
use App\Models\Kader;
use App\Models\Posyandu;
use App\Models\Puskesmas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CRUD-JADWAL — Simpan, update, dan hapus jadwal benar-benar menulis/hapus DB.
 */
class JadwalCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $kaderUser;
    private Posyandu $posyandu;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $puskesmas = Puskesmas::create(['nama' => 'P', 'kode_faskes' => 'P' . uniqid()]);
        $this->posyandu = Posyandu::create(['puskesmas_id' => $puskesmas->id, 'nama' => 'Py', 'desa_kelurahan' => 'Desa']);
        $this->kaderUser = User::create([
            'name' => 'K', 'email' => 'k-jadwal-' . uniqid() . '@t.local',
            'password' => bcrypt('p'), 'role' => 'kader',
        ]);
        Kader::create(['user_id' => $this->kaderUser->id, 'posyandu_id' => $this->posyandu->id, 'nama' => 'K']);
    }

    public function test_store_menyimpan_jadwal_ke_db(): void
    {
        $this->actingAs($this->kaderUser);
        $r = $this->post(route('jadwal.store'), [
            'judul' => 'Penimbangan Umum', 'lokasi' => 'Balai RW 02',
            'tanggal' => now()->addDays(3)->toDateString(),
            'waktu_mulai' => '08:30', 'waktu_selesai' => '11:00', 'catatan' => 'Bawa KIA',
        ]);
        $this->assertDatabaseHas('jadwals', ['posyandu_id' => $this->posyandu->id, 'judul' => 'Penimbangan Umum', 'lokasi' => 'Balai RW 02']);
    }

    public function test_update_mengubah_jadwal_di_db(): void
    {
        $j = Jadwal::create([
            'posyandu_id' => $this->posyandu->id, 'judul' => 'Lama', 'lokasi' => 'X',
            'tanggal' => now()->addDays(2)->toDateString(), 'waktu_mulai' => '09:00', 'waktu_selesai' => '12:00',
        ]);
        $this->actingAs($this->kaderUser);
        $this->put(route('jadwal.update', $j->id), [
            'judul' => 'Penimbangan Baru', 'lokasi' => 'Balai RW 05',
            'tanggal' => now()->addDays(6)->toDateString(),
            'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'catatan' => 'Imunisasi',
        ]);
        $this->assertDatabaseHas('jadwals', ['id' => $j->id, 'judul' => 'Penimbangan Baru']);
    }

    public function test_delete_menghapus_jadwal_dari_db(): void
    {
        $j = Jadwal::create([
            'posyandu_id' => $this->posyandu->id, 'judul' => 'To Delete', 'lokasi' => 'Y',
            'tanggal' => now()->addDays(1)->toDateString(), 'waktu_mulai' => '09:00', 'waktu_selesai' => '12:00',
        ]);
        $id = $j->id;
        $this->actingAs($this->kaderUser);
        $this->delete(route('jadwal.destroy', $id));
        $this->assertSoftDeleted('jadwals', ['id' => $id]);
    }
}
