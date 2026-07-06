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

        $this->actingAs($user, 'backend')->get('/backend')->assertForbidden();
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

        $this->actingAs($admin, 'backend')
            ->get('/backend')
            ->assertOk()
            ->assertSee('KIWI HUMBLE Dashboard');
    }

    public function test_admin_can_update_site_settings(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin, 'backend')
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

        $this->actingAs($admin, 'backend')
            ->post('/backend/members', [
                'name' => 'Lily Family',
                'birthday' => '2019-05-20',
                'mom_name' => 'Mom Lily',
                'mom_phone' => '0912345678',
                'dad_name' => 'Dad Lily',
                'dad_phone' => '0922333444',
                'password' => 'member-password',
                'password_confirmation' => 'member-password',
            ])
            ->assertRedirect('/backend/members');

        $this->assertDatabaseHas('users', [
            'name' => 'Lily Family',
            'birthday' => '2019-05-20 00:00:00',
            'mom_name' => 'Mom Lily',
            'mom_phone' => '0912345678',
            'dad_name' => 'Dad Lily',
            'dad_phone' => '0922333444',
            'login_password' => 'member-password',
            'is_admin' => false,
        ]);
    }

    public function test_admin_cannot_create_duplicate_member_name(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        User::factory()->create(['name' => 'Lily Family', 'is_admin' => false]);

        $this->actingAs($admin, 'backend')
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

        $this->actingAs($admin, 'backend')
            ->put("/backend/members/{$member->id}", [
                'name' => 'Lily Family Updated',
                'birthday' => '2018-06-10',
                'mom_name' => 'Mom Updated',
                'mom_phone' => '0987654321',
                'dad_name' => 'Dad Updated',
                'dad_phone' => '0977777777',
            ])
            ->assertRedirect('/backend/members');

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'name' => 'Lily Family Updated',
            'birthday' => '2018-06-10 00:00:00',
            'mom_name' => 'Mom Updated',
            'mom_phone' => '0987654321',
            'dad_name' => 'Dad Updated',
            'dad_phone' => '0977777777',
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

        $this->actingAs($admin, 'backend')
            ->post("/backend/members/{$member->id}/password", [
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirect("/backend/members/{$member->id}/edit");

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'login_password' => 'new-password',
        ]);

        $this->post('/member/login', [
            'name' => 'Lily Family',
            'password' => 'new-password',
        ])->assertRedirect('/member');
    }

    public function test_backend_members_list_shows_recorded_password(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        User::factory()->create([
            'name' => 'Liam',
            'login_password' => 'family-secret',
            'is_admin' => false,
        ]);

        $this->actingAs($admin, 'backend')
            ->get('/backend/members')
            ->assertOk()
            ->assertSee('family-secret');
    }
}
