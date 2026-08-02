<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_guest_accessing_siswa_page_redirects_to_siswa_login(): void
    {
        $response = $this->get('/siswa/dashboard');

        $response->assertRedirect(route('login.siswa'));
    }

    public function test_guest_accessing_admin_page_redirects_to_petugas_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('login.petugas'));
    }

    public function test_guest_accessing_kasir_page_redirects_to_petugas_login(): void
    {
        $response = $this->get('/kasir/dashboard');

        $response->assertRedirect(route('login.petugas'));
    }

    public function test_role_middleware_redirects_guest_instead_of_500(): void
    {
        $response = $this->get('/siswa/products');

        $response->assertStatus(302);
        $this->assertNotEquals(500, $response->getStatusCode());
    }
}
