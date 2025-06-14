<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test Feature untuk Login Routes
 * 
 * Menguji bahwa route login dan masuk berfungsi dengan baik,
 * dan redirect authentication bekerja dengan benar.
 */
class LoginRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Route 'login' tersedia dan berfungsi
     * 
     * Alur:
     * Input: GET request ke /login
     * Proses: Route mengarah ke login form
     * Output: Status 200 dan form login ditampilkan
     */
    public function test_login_route_is_available()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test: Route 'masuk' tersedia dan berfungsi
     * 
     * Alur:
     * Input: GET request ke /masuk
     * Proses: Route mengarah ke login form
     * Output: Status 200 dan form login ditampilkan
     */
    public function test_masuk_route_is_available()
    {
        $response = $this->get('/masuk');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test: Unauthenticated user redirect ke masuk
     * 
     * Alur:
     * Input: GET request ke halaman admin tanpa login
     * Proses: Middleware auth redirect ke login
     * Output: Redirect ke route 'masuk'
     */
    public function test_unauthenticated_admin_redirects_to_masuk()
    {
        $response = $this->get('/admin/isi-saldo');

        $response->assertStatus(302);
        $response->assertRedirect(route('masuk'));
    }

    /**
     * Test: Authenticated admin dapat akses halaman admin
     * 
     * Alur:
     * Input: GET request ke halaman admin dengan user admin
     * Proses: Middleware auth mengizinkan akses
     * Output: Status 200 dan halaman admin ditampilkan
     */
    public function test_authenticated_admin_can_access_admin_pages()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/isi-saldo');

        $response->assertStatus(200);
        $response->assertViewIs('admin.topup.index');
    }

    /**
     * Test: Non-admin user tidak dapat akses halaman admin
     * 
     * Alur:
     * Input: GET request ke halaman admin dengan user biasa
     * Proses: Middleware role menolak akses
     * Output: Status 403 Forbidden
     */
    public function test_non_admin_user_cannot_access_admin_pages()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/isi-saldo');

        $response->assertStatus(403);
    }

    /**
     * Test: Login dengan route 'login' berfungsi
     * 
     * Alur:
     * Input: POST request ke /login dengan credentials
     * Proses: Authentication dan redirect
     * Output: Redirect ke dashboard
     */
    public function test_login_via_login_route_works()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('dasbor'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: Login dengan route 'masuk' berfungsi
     * 
     * Alur:
     * Input: POST request ke /masuk dengan credentials
     * Proses: Authentication dan redirect
     * Output: Redirect ke dashboard
     */
    public function test_login_via_masuk_route_works()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/masuk', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('dasbor'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: Route names terdaftar dengan benar
     * 
     * Alur:
     * Input: Check route names
     * Proses: Verify route names exist
     * Output: Route names tersedia
     */
    public function test_route_names_are_registered()
    {
        $this->assertTrue(\Route::has('login'));
        $this->assertTrue(\Route::has('masuk'));
        $this->assertTrue(\Route::has('admin.isi-saldo.indeks'));
    }

    /**
     * Test: Guest middleware bekerja dengan benar
     * 
     * Alur:
     * Input: Authenticated user akses login page
     * Proses: Guest middleware redirect
     * Output: Redirect ke dashboard
     */
    public function test_authenticated_user_redirected_from_login_pages()
    {
        $user = User::factory()->create(['role' => 'admin']);

        // Test route 'login'
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect(route('dasbor'));

        // Test route 'masuk'
        $response = $this->actingAs($user)->get('/masuk');
        $response->assertRedirect(route('dasbor'));
    }

    /**
     * Test: Error handling untuk invalid credentials
     * 
     * Alur:
     * Input: POST request dengan credentials salah
     * Proses: Authentication gagal
     * Output: Redirect back dengan error
     */
    public function test_login_with_invalid_credentials()
    {
        $response = $this->post('/masuk', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
