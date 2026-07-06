<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_member_dashboard(): void
    {
        $this->get('/member')->assertRedirect('/member/login');
    }

    public function test_member_can_login_with_name_and_password(): void
    {
        User::factory()->create([
            'name' => 'Lily Family',
            'birthday' => '2019-05-20 00:00:00',
            'mom_name' => 'Mom Lily',
            'mom_phone' => '0912345678',
            'dad_name' => 'Dad Lily',
            'dad_phone' => '0922333444',
            'password' => Hash::make('member-password'),
            'is_admin' => false,
        ]);

        $this->post('/member/login', [
            'name' => 'Lily Family',
            'password' => 'member-password',
        ])->assertRedirect('/member');

        $this->get('/member')
            ->assertOk()
            ->assertSee('Lily Family')
            ->assertSee('2019-05-20')
            ->assertSee('0912345678')
            ->assertSee('Mom Lily')
            ->assertSee('Dad Lily')
            ->assertSee('0922333444');
    }

    public function test_admin_cannot_login_through_member_login(): void
    {
        $this->seed();

        $this->post('/member/login', [
            'name' => 'KIWI Admin',
            'password' => 'password',
        ])
            ->assertSessionHasErrors('name');
    }

    public function test_admin_is_forbidden_from_member_dashboard(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin, 'member')->get('/member')->assertForbidden();
    }

    public function test_member_can_update_family_profile(): void
    {
        $member = User::factory()->create([
            'name' => 'Liam',
            'is_admin' => false,
        ]);

        $this->actingAs($member, 'member')
            ->put('/member', [
                'birthday' => '2019-05-20',
                'mom_name' => 'Mom New',
                'mom_phone' => '0912345678',
                'dad_name' => 'Dad New',
                'dad_phone' => '0922333444',
            ])
            ->assertRedirect('/member');

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'birthday' => '2019-05-20 00:00:00',
            'mom_name' => 'Mom New',
            'mom_phone' => '0912345678',
            'dad_name' => 'Dad New',
            'dad_phone' => '0922333444',
        ]);
    }

    public function test_backend_and_member_logins_can_exist_in_same_session(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create([
            'name' => 'Liam',
            'password' => Hash::make('member-password'),
            'is_admin' => false,
        ]);

        $this->actingAs($admin, 'backend')
            ->actingAs($member, 'member');

        $this->get('/backend')->assertOk();
        $this->get('/member')->assertOk();
    }
}
