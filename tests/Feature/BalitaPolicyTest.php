<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Kader;
use App\Models\OrangTua;
use App\Models\Puskesmas;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * SEDANG-04 — BalitaPolicy (otorisasi terpusat akses data balita).
 */
class BalitaPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Puskesmas $puskesmasA;
    private Puskesmas $puskesmasB;
    private Posyandu $posyanduA;
    private Posyandu $posyanduB;
    private Balita $balitaA;
    private Balita $balitaB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->puskesmasA = Puskesmas::create([
            'nama' => 'Puskesmas A', 'kode_faskes' => 'PKA' . uniqid(),
        ]);
        $this->puskesmasB = Puskesmas::create([
            'nama' => 'Puskesmas B', 'kode_faskes' => 'PKB' . uniqid(),
        ]);

        $this->posyanduA = Posyandu::create([
            'puskesmas_id' => $this->puskesmasA->id, 'nama' => 'Posyandu A', 'desa_kelurahan' => 'Desa A',
        ]);
        $this->posyanduB = Posyandu::create([
            'puskesmas_id' => $this->puskesmasB->id, 'nama' => 'Posyandu B', 'desa_kelurahan' => 'Desa B',
        ]);

        $ortuA = OrangTua::factory()->create();
        $ortuB = OrangTua::factory()->create();

        $this->balitaA = Balita::create([
            'orang_tua_id' => $ortuA->id, 'posyandu_id' => $this->posyanduA->id,
            'nama' => 'AnakA-' . uniqid(), 'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(12)->toDateString(),
        ]);
        $this->balitaB = Balita::create([
            'orang_tua_id' => $ortuB->id, 'posyandu_id' => $this->posyanduB->id,
            'nama' => 'AnakB-' . uniqid(), 'jenis_kelamin' => 'P',
            'tanggal_lahir' => now()->subMonths(10)->toDateString(),
        ]);
    }

    private function kaderUser(Posyandu $posyandu): User
    {
        $user = User::create([
            'name' => 'Kader', 'email' => 'kader-' . uniqid() . '@t.local',
            'password' => bcrypt('x'), 'role' => 'kader',
        ]);
        Kader::create(['user_id' => $user->id, 'posyandu_id' => $posyandu->id, 'nama' => 'Kader']);

        return $user;
    }

    private function puskesmasUser(Puskesmas $puskesmas): User
    {
        $user = User::create([
            'name' => 'Pusk', 'email' => 'pusk-' . uniqid() . '@t.local',
            'password' => bcrypt('x'), 'role' => 'puskesmas',
        ]);
        $puskesmas->update(['user_id' => $user->id]);

        return $user;
    }

    private function ibuUserFor(Balita $balita): User
    {
        // Ibu yang merupakan pemilik (orang_tua) dari balita tsb.
        $user = User::create([
            'name' => 'Ibu', 'email' => 'ibu-' . uniqid() . '@t.local',
            'password' => bcrypt('x'), 'role' => 'ibu',
        ]);
        // Balita sudah punya orang_tua_id; kaitkan user ibu ke OrangTua tsb.
        $orth = $balita->orangTua;
        $orth->update(['user_id' => $user->id]);

        return $user;
    }

    public function test_kader_dapat_akses_balita_di_posyandu_sendiri(): void
    {
        $kader = $this->kaderUser($this->posyanduA);
        $this->assertTrue(Gate::forUser($kader)->allows('view', $this->balitaA));
        $this->assertTrue(Gate::forUser($kader)->allows('update', $this->balitaA));
    }

    public function test_kader_tidak_bisa_akses_balita_posyandu_lain(): void
    {
        $kader = $this->kaderUser($this->posyanduA);
        $this->assertFalse(Gate::forUser($kader)->allows('view', $this->balitaB));
        $this->assertFalse(Gate::forUser($kader)->allows('delete', $this->balitaB));
    }

    public function test_puskesmas_dapat_akses_balita_di_wilayahnya(): void
    {
        $puskesmas = $this->puskesmasUser($this->puskesmasA);
        $this->assertTrue(Gate::forUser($puskesmas)->allows('view', $this->balitaA));
    }

    public function test_puskesmas_tidak_bisa_akses_balita_di_wilayah_lain(): void
    {
        $puskesmas = $this->puskesmasUser($this->puskesmasA);
        $this->assertFalse(Gate::forUser($puskesmas)->allows('view', $this->balitaB));
    }

    public function test_ibu_dapat_akses_balita_miliknya_sendiri(): void
    {
        $ibu = $this->ibuUserFor($this->balitaA);
        $this->assertTrue(Gate::forUser($ibu)->allows('view', $this->balitaA));
    }

    public function test_ibu_tidak_bisa_akses_balita_anak_orang_lain(): void
    {
        $ibu = $this->ibuUserFor($this->balitaA);
        $this->assertFalse(Gate::forUser($ibu)->allows('view', $this->balitaB));
    }

    public function test_role_tanpa_hubungan_ke_balita_ditolak(): void
    {
        // User 'ibu' tanpa relasi OrangTua -> tidak boleh lihat balita mana pun.
        $unlinkedIbu = User::create([
            'name' => 'Ibu Tanpa Data', 'email' => 'anonymous-' . uniqid() . '@t.local',
            'password' => bcrypt('x'), 'role' => 'ibu',
        ]);

        $this->assertFalse(Gate::forUser($unlinkedIbu)->allows('view', $this->balitaA));
        $this->assertFalse(Gate::forUser($unlinkedIbu)->allows('view', $this->balitaB));
    }
}
