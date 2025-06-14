<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * Test Feature untuk Authentication
 *
 * Menguji semua fungsi authentication termasuk:
 * - User registration
 * - User login/logout
 * - Password reset
 * - Role-based access
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat register dengan data yang valid
     *
     * Alur:
     * Input: Form registration dengan name, email, password, password_confirmation
     * Proses: POST ke /register
     * Output: User baru dibuat, redirect ke dashboard, user ter-login
     */
    public function test_user_can_register_with_valid_data()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');

        // User should be created in database
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // User should be authenticated
        $this->assertAuthenticated();

        // Check user was created
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->name);
    }

    /**
     * Test: Registration gagal dengan email yang sudah ada
     *
     * Alur:
     * Input: Form registration dengan email yang sudah terdaftar
     * Proses: POST ke /register
     * Output: Validation error, user tidak dibuat
     */
    public function test_registration_fails_with_existing_email()
    {
        // Create existing user
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        // Should have validation errors
        $response->assertSessionHasErrors(['email']);

        // Should not create duplicate user
        $this->assertEquals(1, User::where('email', 'existing@example.com')->count());
    }

    /**
     * Test: Registration gagal dengan password tidak match
     *
     * Alur:
     * Input: Form registration dengan password dan password_confirmation berbeda
     * Proses: POST ke /register
     * Output: Validation error
     */
    public function test_registration_fails_with_password_mismatch()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ];

        $response = $this->post('/register', $userData);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'john@example.com']);
    }

    /**
     * Test: User dapat login dengan kredensial yang benar
     *
     * Alur:
     * Input: Form login dengan email dan password yang benar
     * Proses: POST ke /login
     * Output: User ter-login, redirect ke dashboard
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals($user->id, auth()->id());
    }

    /**
     * Test: Login gagal dengan kredensial yang salah
     *
     * Alur:
     * Input: Form login dengan email/password yang salah
     * Proses: POST ke /login
     * Output: Validation error, user tidak ter-login
     */
    public function test_login_fails_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test: User dapat logout
     *
     * Alur:
     * Input: User yang sudah login
     * Proses: POST ke /logout
     * Output: User ter-logout, redirect ke home
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test: Admin dapat mengakses admin area
     *
     * Alur:
     * Input: User dengan role 'admin'
     * Proses: GET ke /admin/dashboard
     * Output: Berhasil mengakses admin dashboard
     */
    public function test_admin_can_access_admin_area()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test: Regular user tidak dapat mengakses admin area
     *
     * Alur:
     * Input: User dengan role 'user'
     * Proses: GET ke /admin/dashboard
     * Output: Forbidden atau redirect
     */
    public function test_regular_user_cannot_access_admin_area()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Should be forbidden or redirected
        $this->assertTrue(in_array($response->getStatusCode(), [403, 302]));
    }

    /**
     * Test: Guest tidak dapat mengakses protected routes
     *
     * Alur:
     * Input: User yang belum login
     * Proses: GET ke protected routes
     * Output: Redirect ke login page
     */
    public function test_guest_cannot_access_protected_routes()
    {
        $protectedRoutes = [
            '/dashboard',
            '/profile',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    /**
     * Test: User dapat mengakses profile page
     *
     * Alur:
     * Input: Authenticated user
     * Proses: GET ke /profile
     * Output: Profile page dengan data user
     */
    public function test_authenticated_user_can_access_profile()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Test User');
        $response->assertSee('test@example.com');
    }

    /**
     * Test: User dapat update profile
     *
     * Alur:
     * Input: Form update profile dengan data baru
     * Proses: PATCH ke /profile
     * Output: Profile ter-update, success message
     */
    public function test_user_can_update_profile()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'New Name',
                'email' => 'new@example.com',
            ]);

        $response->assertRedirect('/profile');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
    }

    /**
     * Test: User dapat change password
     *
     * Alur:
     * Input: Form change password dengan current_password, password, password_confirmation
     * Proses: PUT ke /password
     * Output: Password ter-update
     */
    public function test_user_can_change_password()
    {
        $this->markTestSkipped('Password change route not implemented yet');
    }

    /**
     * Test: Change password gagal dengan current password salah
     *
     * Alur:
     * Input: Form change password dengan current_password yang salah
     * Proses: PUT ke /password
     * Output: Validation error
     */
    public function test_change_password_fails_with_wrong_current_password()
    {
        $this->markTestSkipped('Password change route not implemented yet');
    }

    /**
     * Test: Remember me functionality
     *
     * Alur:
     * Input: Login form dengan remember checkbox
     * Proses: POST ke /login dengan remember=true
     * Output: Remember token di-set
     */
    public function test_remember_me_functionality()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        // Check remember token is set
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }
}
