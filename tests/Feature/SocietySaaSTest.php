<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocietySaaSTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    /**
     * Test root route redirects guest to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test login screen renders successfully.
     */
    public function test_login_screen_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Smart Society');
        $response->assertSee('DEMO CREDENTIALS');
    }

    /**
     * Test Super Admin login flow.
     */
    public function test_super_admin_can_authenticate_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login-check', [
            'email' => 'super@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/super-admin/dashboard');
    }

    /**
     * Test Society Admin login flow.
     */
    public function test_society_admin_can_authenticate_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login-check', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Test Resident login flow.
     */
    public function test_resident_can_authenticate_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login-check', [
            'email' => 'resident@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/resident/dashboard');
    }

    /**
     * Test Security Guard login flow.
     */
    public function test_guard_can_authenticate_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login-check', [
            'email' => 'guard@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/guard/dashboard');
    }
}
