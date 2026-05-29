<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_system_log_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('log.system.index'));

        $response->assertOk();
        $response->assertViewIs('pages.log.system.index');
    }

    public function test_audit_log_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('log.audit.index'));

        $response->assertOk();
        $response->assertViewIs('pages.log.audit.index');
    }

    public function test_log_pages_require_authentication(): void
    {
        $this->get(route('log.system.index'))->assertRedirect(route('login'));
        $this->get(route('log.audit.index'))->assertRedirect(route('login'));
    }
}
