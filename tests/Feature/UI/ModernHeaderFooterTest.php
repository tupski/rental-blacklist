<?php

namespace Tests\Feature\UI;

use App\Models\User;
use App\Models\Sponsor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test Feature untuk Modern Header & Footer
 * 
 * Menguji implementasi header dan footer modern dengan:
 * - Primary color #da3544
 * - Navbar gradient design
 * - Footer konsisten di semua halaman
 * - Responsive design
 */
class ModernHeaderFooterTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /**
     * Test: Homepage memiliki navbar modern dengan primary color
     * 
     * Alur:
     * Input: GET request ke homepage
     * Proses: Render halaman dengan navbar modern
     * Output: Navbar dengan gradient merah dan styling modern
     */
    public function test_homepage_has_modern_navbar()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('navbar-modern', false);
        $response->assertSee('linear-gradient(135deg, #da3544 0%, #b82d3c 100%)', false);
        $response->assertSee('RentalGuard'); // Brand name
    }

    /**
     * Test: Homepage memiliki footer modern
     * 
     * Alur:
     * Input: GET request ke homepage
     * Proses: Render halaman dengan footer modern
     * Output: Footer dengan informasi lengkap dan styling modern
     */
    public function test_homepage_has_modern_footer()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('footer-modern', false);
        $response->assertSee('Layanan'); // Footer section
        $response->assertSee('Bantuan'); // Footer section
        $response->assertSee('Kontak'); // Footer section
        $response->assertSee('Semua hak dilindungi'); // Copyright
    }

    /**
     * Test: Admin panel memiliki navbar dengan primary color
     * 
     * Alur:
     * Input: GET request ke admin panel sebagai admin
     * Proses: Render admin layout dengan navbar modern
     * Output: Admin navbar dengan gradient merah
     */
    public function test_admin_panel_has_modern_navbar()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dasbor');

        $response->assertStatus(200);
        $response->assertSee('main-header navbar', false);
        $response->assertSee('linear-gradient(135deg, #da3544 0%, #b82d3c 100%)', false);
        $response->assertSee('navbar-dark', false);
    }

    /**
     * Test: Admin panel memiliki footer modern
     * 
     * Alur:
     * Input: GET request ke admin panel sebagai admin
     * Proses: Render admin layout dengan footer modern
     * Output: Admin footer dengan styling konsisten
     */
    public function test_admin_panel_has_modern_footer()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dasbor');

        $response->assertStatus(200);
        $response->assertSee('main-footer bg-dark', false);
        $response->assertSee('Admin Panel'); // Footer content
        $response->assertSee('v1.0.0'); // Version badge
        $response->assertSee('komunitas rental Indonesia'); // Footer message
    }

    /**
     * Test: Login page memiliki navbar modern
     * 
     * Alur:
     * Input: GET request ke login page
     * Proses: Render login page dengan navbar modern
     * Output: Navbar dengan styling konsisten
     */
    public function test_login_page_has_modern_navbar()
    {
        $response = $this->get('/masuk');

        $response->assertStatus(200);
        $response->assertSee('navbar-modern', false);
        $response->assertSee('Login'); // Login link
        $response->assertSee('Register'); // Register button
    }

    /**
     * Test: Register page memiliki navbar modern
     * 
     * Alur:
     * Input: GET request ke register page
     * Proses: Render register page dengan navbar modern
     * Output: Navbar dengan styling konsisten
     */
    public function test_register_page_has_modern_navbar()
    {
        $response = $this->get('/daftar');

        $response->assertStatus(200);
        $response->assertSee('navbar-modern', false);
        $response->assertSee('btn-light', false); // Register button styling
    }

    /**
     * Test: CSS variables untuk primary color terdefinisi
     * 
     * Alur:
     * Input: GET request ke halaman dengan CSS custom
     * Proses: Check CSS variables
     * Output: CSS variables dengan nilai yang benar
     */
    public function test_css_variables_defined()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('--primary-color: #da3544', false);
        $response->assertSee('--primary-dark: #b82d3c', false);
        $response->assertSee('--primary-light: #e85566', false);
        $response->assertSee('--primary-gradient:', false);
    }

    /**
     * Test: Footer menampilkan sponsor jika ada
     * 
     * Alur:
     * Input: Buat sponsor dan GET request ke homepage
     * Proses: Render footer dengan sponsor
     * Output: Footer menampilkan sponsor dan link "Jadi Sponsor"
     */
    public function test_footer_displays_sponsors_when_available()
    {
        // Create a sponsor
        $sponsor = Sponsor::factory()->create([
            'name' => 'Test Sponsor',
            'website_url' => 'https://example.com',
            'logo_url' => 'https://example.com/logo.png',
            'is_active' => true
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Didukung oleh:');
        $response->assertSee('Test Sponsor');
        $response->assertSee('Jadi Sponsor');
    }

    /**
     * Test: Navbar responsive untuk mobile
     * 
     * Alur:
     * Input: GET request dengan user agent mobile
     * Proses: Render navbar dengan responsive design
     * Output: Navbar dengan toggle button dan mobile styling
     */
    public function test_navbar_is_responsive()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('navbar-toggler', false);
        $response->assertSee('navbar-collapse', false);
        $response->assertSee('data-bs-toggle="collapse"', false);
    }

    /**
     * Test: Footer social links jika tersedia
     * 
     * Alur:
     * Input: GET request ke homepage
     * Proses: Check footer social links
     * Output: Social links dengan styling yang benar
     */
    public function test_footer_social_links()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('social-links', false);
        $response->assertSee('fab fa-facebook-f', false);
        $response->assertSee('fab fa-twitter', false);
        $response->assertSee('fab fa-instagram', false);
        $response->assertSee('fab fa-whatsapp', false);
    }

    /**
     * Test: Button styling konsisten dengan primary color
     * 
     * Alur:
     * Input: GET request ke halaman dengan buttons
     * Proses: Check button styling
     * Output: Buttons menggunakan primary color dan gradient
     */
    public function test_button_styling_consistent()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('btn-primary', false);
        $response->assertSee('btn-light', false);
        $response->assertSee('background: var(--primary-gradient)', false);
    }

    /**
     * Test: Admin topup page memiliki styling konsisten
     * 
     * Alur:
     * Input: GET request ke admin topup page sebagai admin
     * Proses: Render page dengan styling modern
     * Output: Page menggunakan primary color dan styling konsisten
     */
    public function test_admin_topup_page_styling()
    {
        $response = $this->actingAs($this->admin)->get('/admin/isi-saldo');

        $response->assertStatus(200);
        $response->assertSee('text-primary', false);
        $response->assertSee('btn-primary', false);
        $response->assertSee('card-header', false);
    }

    /**
     * Test: Breadcrumb styling dengan primary color
     * 
     * Alur:
     * Input: GET request ke admin page dengan breadcrumb
     * Proses: Check breadcrumb styling
     * Output: Breadcrumb menggunakan primary color untuk active item
     */
    public function test_breadcrumb_styling()
    {
        $response = $this->actingAs($this->admin)->get('/admin/isi-saldo');

        $response->assertStatus(200);
        $response->assertSee('breadcrumb', false);
        $response->assertSee('breadcrumb-item', false);
    }

    /**
     * Test: Animations dan transitions terdefinisi
     * 
     * Alur:
     * Input: GET request ke halaman dengan CSS animations
     * Proses: Check CSS animations
     * Output: CSS animations dan transitions terdefinisi
     */
    public function test_animations_defined()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('transition: all 0.3s ease', false);
        $response->assertSee('transform: translateY(-1px)', false);
        $response->assertSee('box-shadow:', false);
    }
}
