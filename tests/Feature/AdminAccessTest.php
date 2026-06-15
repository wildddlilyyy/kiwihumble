<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_non_admin_is_forbidden_from_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_view_dashboard(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('KIWI GROUP Dashboard');
    }

    public function test_admin_can_update_site_settings(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->post('/admin/settings', [
                'trip_title' => 'KIWI GROUP Humble Test Trip',
                'trip_date' => '2027-06-01',
                'timezone' => 'Asia/Taipei',
            ])
            ->assertRedirect('/admin');

        $this->assertDatabaseHas('site_settings', [
            'key' => 'trip_title',
            'value' => 'KIWI GROUP Humble Test Trip',
        ]);

        $this->assertDatabaseHas('site_settings', [
            'key' => 'trip_date',
            'value' => '2027-06-01',
        ]);
    }
}
