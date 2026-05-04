<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    public function test_login_page_is_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Entrar no painel', false);
    }

    public function test_authenticated_users_are_redirected_away_from_login(): void
    {
        $this->actingAs($this->rootUser())
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_login_page_does_not_render_recaptcha_when_disabled(): void
    {
        Config::set('recaptcha.enabled', false);

        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('data-recaptcha-v3', false);
    }

    public function test_login_page_renders_recaptcha_when_enabled(): void
    {
        Config::set('recaptcha.enabled', true);
        Config::set('recaptcha.site_key', 'site-key');

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('data-recaptcha-v3', false)
            ->assertSee('data-recaptcha-v3-action="login"', false);
    }

    public function test_authenticated_pages_are_not_cached_by_the_browser(): void
    {
        $response = $this->actingAs($this->rootUser())
            ->get(route('dashboard'));

        $response
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0')
            ->assertSee('turbo-cache-control', false);

        $cacheControl = $response->headers->get('Cache-Control');

        $this->assertStringContainsString('no-store', $cacheControl);
        $this->assertStringContainsString('no-cache', $cacheControl);
        $this->assertStringContainsString('must-revalidate', $cacheControl);
        $this->assertStringContainsString('max-age=0', $cacheControl);
    }

    protected function rootUser(): User
    {
        return User::factory()->make([
            'id' => 1,
            'is_root' => true,
            'is_active' => true,
        ]);
    }
}
