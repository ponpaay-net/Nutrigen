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
 * Verifikasi menyeluruh semua halaman & fitur Portal Super Admin (Kemenkes):
 * semua halaman render 200 (tanpa bug sistem/variabel), filter berjalan,
 * export (CSV/Excel/PDF) berfungsi, dan CRUD Puskesmas berjalan.
 */
class SuperAdminFiturTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private Puskesmas $puskesmas;
    private Posyandu $posyandu;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super-fitur-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $pkUser = User::create([
            'name' => 'Puskesmas Fitur',
            'email' => 'pk-fitur-' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'role' => 'puskesmas',
            'email_verified_at' => now(),
        ]);
        $this->puskesmas = Puskesmas::create([
            'user_id' => $pkUser->id,
            'nama' => 'Puskesmas Fitur',
            'kode_faskes' => 'PKF' . uniqid(),
            'kecamatan' => 'Kuta Alam',
            'kabupaten_kota' => 'Banda Aceh',
        ]);

        $this->posyandu = Posyandu::create([
            'puskesmas_id' => $this->puskesmas->id,
            'nama' => 'Posyandu Fitur',
            'desa_kelurahan' => 'Desa Fitur',
        ]);

        $kaderUser = User::create([
            'name' => 'Kader Fitur', 'email' => 'kader-sa-' . uniqid() . '@test.local',
            'password' => bcrypt('password'), 'role' => 'kader',
        ]);
        $kader = Kader::create(['user_id' => $kaderUser->id, 'posyandu_id' => $this->posyandu->id, 'nama' => 'Kader Fitur']);

        $ortu = OrangTua::factory()->create();
        $balita = Balita::create([
            'orang_tua_id' => $ortu->id,
            'posyandu_id' => $this->posyandu->id,
            'nama' => 'Anak Fitur', 'nik' => '1234567890123456',
            'jenis_kelamin' => 'L', 'tanggal_lahir' => now()->subMonths(12)->toDateString(),
        ]);

        Pengukuran::create([
            'balita_id' => $balita->id, 'kader_id' => $kader->id,
            'tanggal_ukur' => now()->toDateString(), 'umur_bulan' => 12,
            'berat_badan' => 9.5, 'tinggi_badan' => 75.0,
            'z_score_bbu' => -0.5, 'z_score_tbu' => -0.3,
            'status_gizi' => 'Normal', 'status_validasi' => 'approved',
        ]);

        $this->actingAs($this->superAdmin);
    }

    public function test_dashboard_dan_filter_bisa_di_render(): void
    {
        $this->get(route('super-admin.dashboard'))->assertStatus(200);
        $this->get(route('super-admin.dashboard', ['month' => 1, 'year' => now()->year]))->assertStatus(200);
        $this->get(route('super-admin.dashboard', ['month' => 12, 'year' => now()->year, 'page' => 1]))->assertStatus(200);
    }

    public function test_index_puskesmas_dan_search_bisa_di_render(): void
    {
        $this->get(route('super-admin.puskesmas.index'))->assertStatus(200);
        $this->get(route('super-admin.puskesmas.index', ['search' => 'Fitur']))->assertStatus(200);
    }

    public function test_show_puskesmas_bisa_di_render(): void
    {
        $this->get(route('super-admin.puskesmas.show', $this->puskesmas->id))->assertStatus(200);
    }

    public function test_export_csv_excel_pdf_bisa_di_generate(): void
    {
        $this->get(route('super-admin.puskesmas.export.csv'))->assertStatus(200);
        $this->get(route('super-admin.puskesmas.export.excel'))->assertStatus(200);
        $this->get(route('super-admin.export.pdf'))->assertStatus(200);
    }

    public function test_store_puskesmas_membuat_user_dan_puskesmas(): void
    {
        $email = 'pk-baru-' . uniqid() . '@test.local';
        $this->post(route('super-admin.puskesmas.store'), [
            'nama' => 'Puskesmas Baru',
            'kode_faskes' => 'PKB' . uniqid(),
            'alamat' => 'Jl. Baru No. 1',
            'kecamatan' => 'Meuraxa',
            'kabupaten_kota' => 'Banda Aceh',
            'email' => $email,
            'password' => 'rahasia123',
        ])->assertRedirect(route('super-admin.puskesmas.index'));

        $this->assertDatabaseHas('puskesmas', ['nama' => 'Puskesmas Baru']);
        $this->assertDatabaseHas('users', ['email' => $email, 'role' => 'puskesmas']);
    }

    public function test_update_puskesmas_mengubah_data(): void
    {
        $this->put(route('super-admin.puskesmas.update', $this->puskesmas->id), [
            'nama' => 'Puskesmas Fitur Diubah',
            'kode_faskes' => $this->puskesmas->kode_faskes,
            'alamat' => 'Jl. Diubah',
            'email' => $this->puskesmas->user->email,
        ])->assertRedirect(route('super-admin.puskesmas.index'));

        $this->assertSame('Puskesmas Fitur Diubah', $this->puskesmas->fresh()->nama);
    }

    public function test_destroy_puskesmas_tanpa_posyandu_berhasil(): void
    {
        $pkUser = User::create([
            'name' => 'PK Kosong', 'email' => 'pk-kosong-' . uniqid() . '@test.local',
            'password' => bcrypt('password'), 'role' => 'puskesmas',
        ]);
        $pk = Puskesmas::create(['user_id' => $pkUser->id, 'nama' => 'PK Kosong', 'kode_faskes' => 'PKK' . uniqid(), 'alamat' => '-']);

        $this->delete(route('super-admin.puskesmas.destroy', $pk->id))->assertRedirect(route('super-admin.puskesmas.index'));
        $this->assertDatabaseMissing('puskesmas', ['id' => $pk->id]);
    }

    public function test_destroy_puskesmas_berposyandu_diblokir(): void
    {
        $this->from(route('super-admin.puskesmas.index'))
            ->delete(route('super-admin.puskesmas.destroy', $this->puskesmas->id))
            ->assertRedirect(route('super-admin.puskesmas.index'));

        // Puskesmas dengan posyandu tidak boleh terhapus
        $this->assertDatabaseHas('puskesmas', ['id' => $this->puskesmas->id]);
    }

    // ===== Fitur baru: detail Posyandu, CRUD Kader, Laporan, Log, Pengaturan =====

    public function test_detail_posyandu_bisa_di_render(): void
    {
        $this->get(route('super-admin.posyandu.show', $this->posyandu->id))->assertStatus(200);
    }

    public function test_tambah_kader_dari_super_admin(): void
    {
        $email = 'kader-baru-' . uniqid() . '@test.local';
        $this->post(route('super-admin.posyandu.kader.store', $this->posyandu->id), [
            'nama' => 'Kader Baru',
            'email' => $email,
            'no_hp' => '081234567890',
            'password' => 'rahasia123',
        ])->assertRedirect(route('super-admin.posyandu.show', $this->posyandu->id));

        $this->assertDatabaseHas('users', ['email' => $email, 'role' => 'kader']);
        $this->assertDatabaseHas('kaders', ['nama' => 'Kader Baru', 'posyandu_id' => $this->posyandu->id]);
    }

    public function test_edit_kader_dari_super_admin(): void
    {
        $kader = Kader::where('posyandu_id', $this->posyandu->id)->first();

        $this->put(route('super-admin.kader.update', $kader->id), [
            'nama' => 'Kader Diubah',
            'email' => $kader->user->email,
            'no_hp' => '081200000000',
        ])->assertRedirect(route('super-admin.posyandu.show', $this->posyandu->id));

        $this->assertSame('Kader Diubah', $kader->fresh()->nama);
    }

    public function test_hapus_kader_dari_super_admin(): void
    {
        $kader = Kader::where('posyandu_id', $this->posyandu->id)->first();
        $userId = $kader->user_id;

        $this->delete(route('super-admin.kader.destroy', $kader->id))
            ->assertRedirect(route('super-admin.posyandu.show', $this->posyandu->id));

        $this->assertDatabaseMissing('kaders', ['id' => $kader->id]);
        // User memakai SoftDeletes -> baris tetap ada dengan deleted_at terisi
        $this->assertSoftDeleted('users', ['id' => $userId]);
    }

    public function test_laporan_nasional_bisa_di_render(): void
    {
        $this->get(route('super-admin.laporan'))->assertStatus(200);
        $this->get(route('super-admin.laporan', ['month' => 1, 'year' => now()->year]))->assertStatus(200);
    }

    public function test_log_aktivitas_bisa_di_render_dan_tercatat(): void
    {
        // Lakukan satu aksi CRUD agar log tercatat
        $email = 'pk-log-' . uniqid() . '@test.local';
        $this->post(route('super-admin.puskesmas.store'), [
            'nama' => 'Puskesmas Log', 'kode_faskes' => 'PKL' . uniqid(),
            'alamat' => 'Jl. Log', 'email' => $email, 'password' => 'rahasia123',
        ]);

        $this->assertDatabaseHas('audit_logs', ['action' => 'create']);
        $this->get(route('super-admin.log'))->assertStatus(200);
    }

    public function test_pengaturan_sistem_bisa_di_render_dan_disimpan(): void
    {
        $this->get(route('super-admin.pengaturan'))->assertStatus(200);

        $this->put(route('super-admin.pengaturan.update'), [
            'institution_name' => 'Kemenkes RI',
            'institution_unit' => 'Direktorat Gizi',
            'stunting_threshold' => 20,
            'contact_email' => 'admin@nutrigen.go.id',
            'data_source' => 'NutriGen',
        ])->assertRedirect(route('super-admin.pengaturan'));

        $this->assertDatabaseHas('settings', ['key' => 'stunting_threshold', 'value' => '20']);
    }
}