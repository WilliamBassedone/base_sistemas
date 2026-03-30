<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ModuleRegistrationTest extends TestCase
{
    public function test_public_module_pages_are_registered(): void
    {
        $this->get('/dashboard')->assertOk();
        $this->get('/configuracoes')->assertOk();
        $this->get('/configuracoes/grupos')->assertOk();
        $this->get('/configuracoes/empresas')->assertOk();
        $this->get('/configuracoes/tokens')->assertOk();
        $this->get('/blog/turbo')->assertOk();
        $this->get('/novo/turbo')->assertOk();
    }

    public function test_module_route_names_are_available(): void
    {
        $this->assertTrue(Route::has('dashboard'));
        $this->assertTrue(Route::has('api.panel.index'));
        $this->assertTrue(Route::has('groups.index'));
        $this->assertTrue(Route::has('companies.index'));
        $this->assertTrue(Route::has('tokens.index'));
        $this->assertTrue(Route::has('blog.turbo'));
        $this->assertTrue(Route::has('novo.turbo'));
        $this->assertTrue(Route::has('blog.index'));
        $this->assertTrue(Route::has('ui.index'));
        $this->assertTrue(Route::has('api.blog.index'));
        $this->assertTrue(Route::has('api.ui.index'));
    }
}
