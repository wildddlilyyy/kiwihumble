<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_backend(): void
    {
        $this->get('/backend')->assertRedirect('/backend/login');
    }

    public function test_non_admin_is_forbidden_from_backend(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/backend')->assertForbidden();
    }

    public function test_admin_can_login_to_backend(): void
    {
        $this->seed();

        $this->post('/backend/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect('/backend');
    }

    public function test_admin_can_view_backend_dashboard(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->get('/backend')
            ->assertOk()
            ->assertSee('KIWI HUMBLE Dashboard');
    }

    public function test_admin_can_update_site_settings(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->post('/backend/settings', [
                'trip_title' => 'KIWI GROUP Humble Test Trip',
                'trip_date' => '2027-06-01',
                'timezone' => 'Asia/Taipei',
            ])
            ->assertRedirect('/backend');

        $this->assertDatabaseHas('site_settings', [
            'key' => 'trip_title',
            'value' => 'KIWI GROUP Humble Test Trip',
        ]);

        $this->assertDatabaseHas('site_settings', [
            'key' => 'trip_date',
            'value' => '2027-06-01',
        ]);
    }

    public function test_admin_can_create_member(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->post('/backend/members', [
                'name' => 'Lily Family',
                'phone' => '0912345678',
                'mom_name' => 'Mom Lily',
                'dad_name' => 'Dad Lily',
                'password' => 'member-password',
                'password_confirmation' => 'member-password',
            ])
            ->assertRedirect('/backend/members');

        $this->assertDatabaseHas('users', [
            'name' => 'Lily Family',
            'phone' => '0912345678',
            'mom_name' => 'Mom Lily',
            'dad_name' => 'Dad Lily',
            'is_admin' => false,
        ]);
    }

    public function test_admin_cannot_create_duplicate_member_name(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        User::factory()->create(['name' => 'Lily Family', 'is_admin' => false]);

        $this->actingAs($admin)
            ->from('/backend/members/create')
            ->post('/backend/members', [
                'name' => 'Lily Family',
                'password' => 'member-password',
                'password_confirmation' => 'member-password',
            ])
            ->assertRedirect('/backend/members/create')
            ->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_member_profile(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create(['name' => 'Lily Family', 'is_admin' => false]);

        $this->actingAs($admin)
            ->put("/backend/members/{$member->id}", [
                'name' => 'Lily Family Updated',
                'phone' => '0987654321',
                'mom_name' => 'Mom Updated',
                'dad_name' => 'Dad Updated',
            ])
            ->assertRedirect('/backend/members');

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'name' => 'Lily Family Updated',
            'phone' => '0987654321',
            'mom_name' => 'Mom Updated',
            'dad_name' => 'Dad Updated',
        ]);
    }

    public function test_admin_can_reset_member_password(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create([
            'name' => 'Lily Family',
            'password' => Hash::make('old-password'),
            'is_admin' => false,
        ]);

        $this->actingAs($admin)
            ->post("/backend/members/{$member->id}/password", [
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirect("/backend/members/{$member->id}/edit");

        $this->post('/logout');

        $this->post('/member/login', [
            'name' => 'Lily Family',
            'password' => 'new-password',
        ])->assertRedirect('/member');
    }
}
