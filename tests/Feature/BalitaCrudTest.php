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
 * CRUD-01 — Alur inti CRUD balita kader: simpan, update, dan hapus harus
 * benar-benar menulis/menghapus baris di database.
 */
class BalitaCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $kaderUser;
    private Posyandu $posyandu;
    private Balita $balita;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $puskesmas = Puskesmas::create(['nama' => 'Puskesmas A', 'kode_faskes' => 'PKA' . uniqid()]);
        $this->posyandu = Posyandu::create(['puskesmas_id' => $puskesmas->id, 'nama' => 'Posyandu A', 'desa_kelurahan' => 'Desa A']);

        $this->kaderUser = User::create([
            'name' => 'Kader A', 'email' => 'kader-crud-' . uniqid() . '@test.local',
            'password' => bcrypt('password'), 'role' => 'kader',
        ]);
        Kader::create(['user_id' => $this->kaderUser->id, 'posyandu_id' => $this->posyandu->id, 'nama' => 'Kader A']);

        $ortu = OrangTua::factory()->create();
        $this->balita = Balita::create([
            'orang_tua_id' => $ortu->id, 'posyandu_id' => $this->posyandu->id,
            'nama' => 'AnakCrud-' . uniqid(), 'nik' => $this->nik16(),
            'jenis_kelamin' => 'L', 'tanggal_lahir' => now()->subMonths(12)->toDateString(),
        ]);
    }

    private function nik16(): string
    {
        return collect(range(1, 16))->map(fn () => mt_rand(0, 9))->implode('');
    }

    /** Simpan (create) harus membuat baris balita + ortu di DB */
    public function test_create_menyimpankan_balita_ke_database(): void
    {
        $this->actingAs($this->kaderUser);
        $nik = $this->nik16();

        $response = $this->post(route('balita.store'), [
            'nama' => 'Aisyah Putri', 'nik' => $nik, 'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2025-01-01', 'berat_lahir' => '3.2', 'panjang_lahir' => '49.5', 'lingkar_kepala_lahir' => '33.5',
            'no_kk' => $this->nik16(), 'nama_ibu' => 'Siti Aminah', 'no_hp' => '081234567890',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga', 'desa' => 'Gampong Serambi', 'kecamatan' => 'Meuraxa',
        ]);

        $response->assertRedirect(route('balita.index'));
        $response->assertSessionHas('success');
        $balita = Balita::where('nama', 'Aisyah Putri')->first();
        $this->assertNotNull($balita);
        $this->assertSame($nik, $balita->nik); // nik di-decrypt oleh model (tersimpan terenkripsi)
        $this->assertSame('P', $balita->jenis_kelamin);
        $this->assertSame($this->posyandu->id, $balita->posyandu_id);
        $this->assertDatabaseHas('orang_tuas', ['nama_ibu' => 'Siti Aminah', 'no_hp_whatsapp' => '081234567890']);
    }

    /** Update harus benar-benar mengubah data di DB */
    public function test_update_mengubah_data_di_database(): void
    {
        $this->actingAs($this->kaderUser);

        $response = $this->put(route('balita.update', $this->balita->id), [
            'nama' => 'Muhammad Al-Fatih', 'nik' => $this->balita->nik, 'jenis_kelamin' => $this->balita->jenis_kelamin,
            'tanggal_lahir' => $this->balita->tanggal_lahir->toDateString(),
            'berat_lahir' => '3.9', 'panjang_lahir' => '51.0', 'lingkar_kepala_lahir' => '34.5',
            'nama_ibu' => 'Siti Aminah', 'no_hp' => '081234567890', 'desa' => 'Gampong Baru', 'kecamatan' => 'Meuraxa',
        ]);

        $response->assertRedirect(route('balita.show', $this->balita->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('balitas', ['id' => $this->balita->id, 'nama' => 'Muhammad Al-Fatih', 'berat_lahir' => 3.9]);
    }

    /** Delete harus menghapus balita + pengukuran + (orang tua tanpa anak lain) dari DB */
    public function test_delete_menghapus_balita_dan_relasinya_dari_database(): void
    {
        // Balita sudah punya pengukuran -> harus ikut terhapus
        Pengukuran::create([
            'balita_id'      => $this->balita->id,
            'kader_id'       => Kader::where('user_id', $this->kaderUser->id)->first()->id,
            'tanggal_ukur'   => now()->toDateString(),
            'umur_bulan'     => 12,
            'berat_badan'    => 9.5, 'tinggi_badan' => 75.0,
            'status_validasi' => 'pending', 'status_gizi' => 'Normal',
        ]);

        $this->actingAs($this->kaderUser);
        $balitaId = $this->balita->id;
        $ortuId   = $this->balita->orang_tua_id;

        $response = $this->delete(route('balita.destroy', $balitaId));
        $response->assertRedirect(route('balita.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('balitas', ['id' => $balitaId]);
        $this->assertDatabaseMissing('pengukurans', ['balita_id' => $balitaId]);
        // Karena ortu tidak punya anak lain -> ikut terhapus
        $this->assertDatabaseMissing('orang_tuas', ['id' => $ortuId]);
    }
}
