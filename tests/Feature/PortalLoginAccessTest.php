<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Kader;
use App\Models\OrangTua;
use App\Models\Posyandu;
use App\Models\Puskesmas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menjamin alur login & otorisasi akses per portal berjalan benar:
 * setiap role diarahkan ke dashboard masing-masing, dan role lain
 * tidak bisa mengakses halaman portal yang bukan miliknya.
 */
class PortalLoginAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_kader_login_diarahkan_ke_dashboard_kader(): void
    {
        $puskesmas = Puskesmas::create(['nama' => 'Puskesmas A', 'kode_faskes' => 'PKA1']);
        $posyandu = Posyandu::create(['puskesmas_id' => $puskesmas->id, 'nama' => 'Posyandu A', 'desa_kelurahan' => 'Desa']);
        $email = 'kader-login-' . uniqid() . '@test.local';
        $user = User::create(['name'=>'Kader A','email'=>$email,'password'=>bcrypt('password'),'role'=>'kader']);
        Kader::create(['user_id'=>$user->id,'posyandu_id'=>$posyandu->id,'nama'=>'Kader A']);

        $response = $this->post(route('login'), ['email'=>$email,'password'=>'password']);
        $response->assertStatus(302);
        $this->assertStringContainsString(route('kader.dashboard'), $response->headers->get('Location') ?? '');
        $this->assertAuthenticatedAs($user);
    }

    public function test_super_admin_login_diarahkan_ke_dashboard_super_admin(): void
    {
        $email = 'super-login-' . uniqid() . '@test.local';
        $user = User::create(['name'=>'Super','email'=>$email,'password'=>bcrypt('password'),'role'=>'super_admin','email_verified_at'=>now()]);

        $response = $this->post(route('login'), ['email'=>$email,'password'=>'password']);
        $response->assertStatus(302);
        $this->assertStringContainsString(route('super-admin.dashboard'), $response->headers->get('Location') ?? '');
        $this->assertAuthenticatedAs($user);
    }

    public function test_puskesmas_login_diarahkan_ke_dashboard_puskesmas(): void
    {
        $email = 'puskesmas-login-' . uniqid() . '@test.local';
        $user = User::create(['name'=>'Puskesmas','email'=>$email,'password'=>bcrypt('password'),'role'=>'puskesmas','email_verified_at'=>now()]);
        Puskesmas::create(['user_id'=>$user->id,'nama'=>'Puskesmas','kode_faskes'=>'PKX']);

        $response = $this->post(route('login'), ['email'=>$email,'password'=>'password']);
        $response->assertStatus(302);
        $this->assertStringContainsString(route('puskesmas.dashboard'), $response->headers->get('Location') ?? '');
        $this->assertAuthenticatedAs($user);
    }

    public function test_ibu_login_memakai_signed_url_portal_ibu(): void
    {
        $puskesmas = Puskesmas::create(['nama' => 'Puskesmas A', 'kode_faskes' => 'PKA1']);
        $posyandu = Posyandu::create(['puskesmas_id' => $puskesmas->id, 'nama' => 'Posyandu A', 'desa_kelurahan' => 'Desa']);
        $email = 'ibu-login-' . uniqid() . '@test.local';
        $user = User::create(['name'=>'Ibu','email'=>$email,'password'=>bcrypt('password'),'role'=>'ibu','email_verified_at'=>now()]);
        $ortu = OrangTua::create(['user_id'=>$user->id,'nama_ibu'=>'Ibu','no_hp_whatsapp'=>'081234567890']);
        Balita::create(['orang_tua_id'=>$ortu->id,'posyandu_id'=>$posyandu->id,'nama'=>'Anak','nik'=>'1234567890123456','jenis_kelamin'=>'L','tanggal_lahir'=>now()->subMonths(12)]);

        $response = $this->post(route('login'), ['email'=>$email,'password'=>'password']);
        $response->assertStatus(302);
        $this->assertStringContainsString('portal-ibu', $response->headers->get('Location') ?? '');
        $this->assertAuthenticatedAs($user);
    }

    public function test_non_kader_tidak_bisa_akses_halaman_kader(): void
    {
        $puskesmas = Puskesmas::create(['nama'=>'Puskesmas A','kode_faskes'=>'PKA1']);
        $email = 'puskesmas-akses-' . uniqid() . '@test.local';
        $user = User::create(['name'=>'Puskesmas','email'=>$email,'password'=>bcrypt('password'),'role'=>'puskesmas','email_verified_at'=>now()]);
        Puskesmas::create(['user_id'=>$user->id,'nama'=>'Puskesmas','kode_faskes'=>'PKY']);

        $this->actingAs($user);
        // Route kader dilindungi middleware role:kader -> 403
        $response = $this->get(route('balita.index'));
        $response->assertStatus(403);
    }
}