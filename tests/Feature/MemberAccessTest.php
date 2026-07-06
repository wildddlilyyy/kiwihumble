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
            'phone' => '0912345678',
            'mom_name' => 'Mom Lily',
            'dad_name' => 'Dad Lily',
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
            ->assertSee('0912345678')
            ->assertSee('Mom Lily')
            ->assertSee('Dad Lily');
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

        $this->actingAs($admin)->get('/member')->assertForbidden();
    }
}
