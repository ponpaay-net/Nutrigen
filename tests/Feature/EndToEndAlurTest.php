<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Kader;
use App\Models\Pengukuran;
use App\Models\Posyandu;
use App\Models\Puskesmas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * UJI END-TO-END lintas portal — menirukan alur nyata presentasi:
 *   Super Admin  -> buat Puskesmas (+akun login)
 *   Puskesmas    -> login, buat Posyandu & Kader, validasi pengukuran
 *   Kader        -> login, daftarkan balita, ukur, kirim sesi
 *   Ibu          -> buka link unik (signed) & semua halamannya
 * Plus pemeriksaan keamanan: RBAC 403, signed URL anti-IDOR/anti-tamper.
 */
class EndToEndAlurTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        return User::create([
            'name' => 'Super Admin', 'email' => 'sa-e2e-' . uniqid() . '@test.local',
            'password' => bcrypt('password'), 'role' => 'super_admin', 'email_verified_at' => now(),
        ]);
    }

    public function test_alur_lengkap_dari_super_admin_sampai_portal_ibu(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1. SUPER ADMIN: login -> dashboard -> tambah Puskesmas
        // ─────────────────────────────────────────────────────────────
        $sa = $this->superAdmin();
        $this->actingAs($sa)->get(route('super-admin.dashboard'))->assertOk();

        $pkEmail = 'pk-e2e-' . uniqid() . '@test.local';
        $this->post(route('super-admin.puskesmas.store'), [
            'nama' => 'Puskesmas E2E',
            'kode_faskes' => 'PKE' . uniqid(),
            'alamat' => 'Jl. E2E No. 1',
            'kecamatan' => 'Kuta Alam',
            'kabupaten_kota' => 'Banda Aceh',
            'email' => $pkEmail,
            'password' => 'password',
        ])->assertRedirect(route('super-admin.puskesmas.index'));

        $pkUser = User::where('email', $pkEmail)->firstOrFail();
        $puskesmas = Puskesmas::where('user_id', $pkUser->id)->firstOrFail();

        // ─────────────────────────────────────────────────────────────
        // 2. PUSKESMAS: login -> dashboard -> buat Posyandu -> buat Kader
        // ─────────────────────────────────────────────────────────────
        $this->actingAs($pkUser);
        $this->get(route('puskesmas.dashboard'))->assertOk();

        $this->post(route('puskesmas.posyandu.store'), [
            'nama' => 'Posyandu E2E',
            'desa_kelurahan' => 'Desa E2E',
            'alamat' => 'Balai E2E',
        ])->assertRedirect();

        $posyandu = Posyandu::where('puskesmas_id', $puskesmas->id)->firstOrFail();

        $kaderEmail = 'kader-e2e-' . uniqid() . '@test.local';
        $this->post(route('puskesmas.posyandu.kader.store', $posyandu->id), [
            'nama' => 'Kader E2E',
            'email' => $kaderEmail,
            'no_hp' => '081234567890',
            'password' => 'password',
        ])->assertRedirect();

        $kaderUser = User::where('email', $kaderEmail)->firstOrFail();
        $kader = Kader::where('user_id', $kaderUser->id)->firstOrFail();
        $this->assertSame('kader', $kaderUser->role);

        // ─────────────────────────────────────────────────────────────
        // 3. KADER: login -> dashboard -> daftarkan balita -> ukur -> kirim sesi
        // ─────────────────────────────────────────────────────────────
        $this->actingAs($kaderUser);
        $this->get(route('kader.dashboard'))->assertOk();
        $this->get(route('balita.index'))->assertOk();

        $this->post(route('balita.store'), [
            'nama' => 'Balita E2E', 'nik' => '1171010101010001', 'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(12)->toDateString(),
            'berat_lahir' => '3.2', 'panjang_lahir' => '49.5', 'lingkar_kepala_lahir' => '33.5',
            'no_kk' => '1171010101010002', 'nama_ibu' => 'Ibu E2E', 'nik_ibu' => '1171010101010003',
            'no_hp' => '081298765432', 'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'desa' => 'Desa E2E', 'kecamatan' => 'Kuta Alam',
        ])->assertRedirect(route('balita.index'));

        $balita = Balita::where('nama', 'Balita E2E')->firstOrFail();

        $this->get(route('balita.ukur', $balita->id))->assertOk();
        $this->post(route('pengukuran.store'), [
            'balita_id' => $balita->id,
            'tanggal_ukur' => now()->toDateString(),
            'berat_badan' => '9.5', 'tinggi_badan' => '75.0', 'lingkar_kepala' => '44.0',
            'asi_eksklusif' => '1', 'status_kenaikan' => 'N', 'catatan_kader' => 'Tumbuh baik',
        ])->assertRedirect(route('balita.show', $balita->id));

        $pengukuran = Pengukuran::where('balita_id', $balita->id)->firstOrFail();
        $this->assertSame('draft', $pengukuran->status_validasi);

        // Kirim sesi -> draft menjadi pending
        $this->post(route('sesi.kirim'), ['catatan_kader' => 'Sesi lancar'])->assertRedirect();
        $this->assertSame('pending', $pengukuran->fresh()->status_validasi);

        // SesiPosyandu tercatat dengan kader_id = id USER (FK ke users.id)
        $this->assertDatabaseHas('sesi_posyandus', [
            'posyandu_id' => $posyandu->id,
            'kader_id'    => $kaderUser->id,
            'status'      => 'dikirim',
        ]);

        // ─────────────────────────────────────────────────────────────
        // 4. PUSKESMAS: validasi -> setujui -> dapat link portal ibu
        // ─────────────────────────────────────────────────────────────
        $this->actingAs($pkUser);
        $this->get(route('puskesmas.validasi'))->assertOk();
        $this->get(route('puskesmas.validasi.review', $pengukuran->id))->assertOk();

        $approve = $this->post(route('puskesmas.validasi.approve', $pengukuran->id), [
            'catatan_validator' => 'Data valid, pertumbuhan normal.',
        ]);
        $approve->assertRedirect(route('puskesmas.validasi'));
        $this->assertSame('approved', $pengukuran->fresh()->status_validasi);
        $approve->assertSessionHas('portal_link');

        $portalUrl = session('portal_link')['url'] ?? null;
        $this->assertNotEmpty($portalUrl, 'Link portal ibu harus tersedia setelah approve.');

        // ─────────────────────────────────────────────────────────────
        // 5. IBU: buka link unik -> semua halaman portal
        // ─────────────────────────────────────────────────────────────
        auth()->logout();
        $this->get($portalUrl)->assertOk();
        $this->get($portalUrl)->assertSee('Balita E2E', false);

        $ortuId = $balita->orang_tua_id;
        $pages = ['portal-ibu.growth', 'portal-ibu.nutrition', 'portal-ibu.posyandu'];
        foreach ($pages as $route) {
            $url = URL::temporarySignedRoute($route, now()->addDays(1), [
                'balita' => $balita->id, 'orang_tua' => $ortuId,
            ]);
            $this->get($url)->assertOk();
        }

        // Semua dashboard portal lain tetap bisa dibuka
        $this->actingAs($sa)->get(route('super-admin.laporan'))->assertOk();
        $this->actingAs($sa)->get(route('super-admin.log'))->assertOk();
        $this->actingAs($pkUser)->get(route('puskesmas.laporan'))->assertOk();
        $this->actingAs($kaderUser)->get(route('laporan.index'))->assertOk();
    }

    public function test_keamanan_rbac_lintas_portal(): void
    {
        $kader = User::create(['name'=>'K','email'=>'rbac-k-'.uniqid().'@t.local','password'=>bcrypt('password'),'role'=>'kader']);
        $pkm = User::create(['name'=>'P','email'=>'rbac-p-'.uniqid().'@t.local','password'=>bcrypt('password'),'role'=>'puskesmas']);
        $sa = $this->superAdmin();

        // Kader tidak boleh akses puskesmas & super-admin
        $this->actingAs($kader)->get(route('puskesmas.dashboard'))->assertStatus(403);
        $this->actingAs($kader)->get(route('super-admin.dashboard'))->assertStatus(403);

        // Puskesmas tidak boleh akses kader & super-admin
        $this->actingAs($pkm)->get(route('kader.dashboard'))->assertStatus(403);
        $this->actingAs($pkm)->get(route('super-admin.dashboard'))->assertStatus(403);

        // Super admin tidak boleh akses kader & puskesmas
        $this->actingAs($sa)->get(route('kader.dashboard'))->assertStatus(403);
        $this->actingAs($sa)->get(route('puskesmas.dashboard'))->assertStatus(403);
    }

    public function test_keamanan_signed_url_anti_tamper_dan_idor(): void
    {
        $puskesmas = Puskesmas::create(['nama'=>'PK','kode_faskes'=>'TPK'.uniqid()]);
        $posyandu = Posyandu::create(['puskesmas_id'=>$puskesmas->id,'nama'=>'Pos','desa_kelurahan'=>'D']);
        $ortuA = \App\Models\OrangTua::factory()->create();
        $ortuB = \App\Models\OrangTua::factory()->create();
        $balitaA = Balita::create(['orang_tua_id'=>$ortuA->id,'posyandu_id'=>$posyandu->id,'nama'=>'Anak A','jenis_kelamin'=>'L','tanggal_lahir'=>now()->subMonths(12)]);
        $balitaB = Balita::create(['orang_tua_id'=>$ortuB->id,'posyandu_id'=>$posyandu->id,'nama'=>'Anak B','jenis_kelamin'=>'P','tanggal_lahir'=>now()->subMonths(10)]);

        // Signature kedaluwarsa -> 403
        $expired = URL::temporarySignedRoute('portal-ibu.home', now()->subMinute(), ['balita'=>$balitaA->id,'orang_tua'=>$ortuA->id]);
        $this->get($expired)->assertStatus(403);

        // Tamper balita id -> 403
        $valid = URL::temporarySignedRoute('portal-ibu.home', now()->addDay(), ['balita'=>$balitaA->id,'orang_tua'=>$ortuA->id]);
        $tampered = str_replace("balita={$balitaA->id}", "balita={$balitaB->id}", $valid);
        $this->get($tampered)->assertStatus(403);

        // Signature valid tapi balita bukan milik ortu -> data anak lain tidak bocor
        $cross = URL::temporarySignedRoute('portal-ibu.home', now()->addDay(), ['balita'=>$balitaB->id,'orang_tua'=>$ortuA->id]);
        $this->get($cross)->assertOk()->assertDontSee('Anak B', false);
    }

    public function test_route_reset_database_tidak_terekspos_di_luar_lokal(): void
    {
        // Route /refresh-database-nutrigen (penghapus DB) hanya boleh terdaftar
        // di environment 'local'. Di luar itu harus 404 (tidak dapat diakses).
        $this->get('/refresh-database-nutrigen')->assertNotFound();
    }

    public function test_keamanan_data_sensitif_terenkripsi_di_database(): void
    {
        $puskesmas = Puskesmas::create(['nama'=>'PK2','kode_faskes'=>'TPK2'.uniqid()]);
        $posyandu = Posyandu::create(['puskesmas_id'=>$puskesmas->id,'nama'=>'Pos2','desa_kelurahan'=>'D']);
        $ortu = \App\Models\OrangTua::factory()->create(['no_hp_whatsapp' => '081200001111', 'no_kk' => '1171010101019999']);
        $balita = Balita::create([
            'orang_tua_id'=>$ortu->id,'posyandu_id'=>$posyandu->id,'nama'=>'Anak Sensitif',
            'nik'=>'1171010101018888','jenis_kelamin'=>'L','tanggal_lahir'=>now()->subMonths(12),
        ]);

        // Nilai mentah di DB harus terenkripsi (bukan plaintext)
        $rawNik = \DB::table('balitas')->where('id', $balita->id)->value('nik');
        $this->assertNotSame('1171010101018888', $rawNik);
        $rawKk = \DB::table('orang_tuas')->where('id', $ortu->id)->value('no_kk');
        $this->assertNotSame('1171010101019999', $rawKk);

        // Tapi tetap terbaca lewat model (cast dekripsi)
        $this->assertSame('1171010101018888', $balita->fresh()->nik);
        $this->assertSame('1171010101019999', $ortu->fresh()->no_kk);
    }
}