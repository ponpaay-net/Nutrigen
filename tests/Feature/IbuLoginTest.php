<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\OrangTua;
use App\Models\Posyandu;
use App\Models\Puskesmas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Alur masuk akun Ibu — memastikan setelah login manual, role 'ibu'
 * selalu diarahkan ke portal-ibu via temporary signed URL (anti-IDOR),
 * bukan ke halaman lain maupun error 419.
 */
class IbuLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_ibu_login_redirect_ke_signed_portal_ibu(): void
    {
        $puskesmas = Puskesmas::create(['nama' => 'Puskesmas Demo', 'kode_faskes' => 'PD1']);
        $posyandu = Posyandu::create(['puskesmas_id' => $puskesmas->id, 'nama' => 'Posyandu Demo', 'desa_kelurahan' => 'Desa']);

        $email = 'ibu-demo-' . uniqid() . '@nutrigen.com';
        $userIbu = User::create([
            'name' => 'Ibu Demo',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => 'ibu',
        ]);
        $ortu = OrangTua::create([
            'user_id' => $userIbu->id,
            'nama_ibu' => 'Ibu Demo',
            'no_hp_whatsapp' => '081234567890',
        ]);
        Balita::create([
            'orang_tua_id' => $ortu->id,
            'posyandu_id' => $posyandu->id,
            'nama' => 'Anak Demo',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(12)->toDateString(),
        ]);

        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertStringContainsString('portal-ibu', $response->headers->get('Location') ?? '');
        $this->assertAuthenticatedAs($userIbu);
    }

    public function test_ibu_dengan_password_salah_tidak_diarahkan_ke_portal(): void
    {
        $email = 'ibu-salah-' . uniqid() . '@nutrigen.com';
        User::create([
            'name' => 'Ibu Salah',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => 'ibu',
        ]);

        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => 'salah',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}