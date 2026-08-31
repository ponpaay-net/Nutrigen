<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        // Registrasi publik memang dinonaktifkan (route dikomentari di routes/auth.php);
        // akun petugas dibuat/dikelola oleh pihak Puskesmas. Maka /register = 404.
        $response = $this->get('/register');

        $response->assertNotFound();
    }

    public function test_new_users_can_register(): void
    {
        // Endpoint registrasi publik tidak ada (404) — bukan bagian alur aplikasi.
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertNotFound();
        $this->assertGuest();
    }
}
