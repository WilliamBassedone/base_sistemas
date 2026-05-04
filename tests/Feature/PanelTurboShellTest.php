<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PanelTurboShellTest extends TestCase
{
    public function test_panel_full_request_renders_shell(): void
    {
        $response = $this->actingAs($this->rootUser())->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('id="panel-sidebar"', false);
        $response->assertSee('<turbo-frame id="main">', false);
    }

    public function test_panel_frame_request_renders_only_the_requested_frame(): void
    {
        $response = $this
            ->actingAs($this->rootUser())
            ->withHeader('Turbo-Frame', 'main')
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('<turbo-frame id="main">', false);
        $response->assertDontSee('id="panel-sidebar"', false);
        $response->assertSee('data-panel-page', false);
    }

    public function test_group_index_frame_request_renders_inside_the_panel_frame(): void
    {
        $response = $this
            ->actingAs($this->rootUser())
            ->withHeader('Turbo-Frame', 'main')
            ->get(route('groups.index'));

        $response->assertOk();
        $response->assertSee('<turbo-frame id="main">', false);
        $response->assertDontSee('id="panel-sidebar"', false);
        $response->assertSee('Listagem de Grupos', false);
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
