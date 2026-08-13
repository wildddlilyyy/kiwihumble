<?php

namespace Tests\Feature;

use App\Models\ClassShirtOrder;
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

    public function test_admin_can_create_member_with_short_or_special_character_password(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin, 'backend')
            ->post('/backend/members', [
                'name' => 'Short Password Member',
                'password' => 'M!',
                'password_confirmation' => 'M!',
            ])
            ->assertRedirect('/backend/members');

        $this->assertDatabaseHas('users', [
            'name' => 'Short Password Member',
            'login_password' => 'M!',
            'is_admin' => false,
        ]);
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

    public function test_backend_members_list_shows_class_shirt_payment_summary(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create([
            'name' => 'Liam',
            'is_admin' => false,
        ]);

        ClassShirtOrder::query()->create([
            'user_id' => $member->id,
            'items' => [
                ['category' => 'adult', 'size' => 'L', 'quantity' => 3],
            ],
            'submitted_at' => now(),
            'payment_method' => 'transfer',
            'payment_account_last_five' => '40132',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($admin, 'backend')
            ->get('/backend/members')
            ->assertOk()
            ->assertSee('Shirts')
            ->assertSee('3 pcs')
            ->assertSee('NT$ 900')
            ->assertSee('匯款')
            ->assertSee('40132')
            ->assertSee('付款待確認')
            ->assertSee("/backend/members/{$member->id}/class-shirt-order", false);
    }

    public function test_backend_can_view_and_update_member_class_shirt_order_with_payment(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create([
            'name' => 'Liam',
            'is_admin' => false,
        ]);

        $order = ClassShirtOrder::query()->create([
            'user_id' => $member->id,
            'items' => [
                ['category' => 'adult', 'size' => 'L', 'quantity' => 3],
            ],
            'submitted_at' => now()->subDay(),
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
        ]);
        $submittedAt = $order->submitted_at;

        $this->actingAs($admin, 'backend')
            ->get("/backend/members/{$member->id}/class-shirt-order")
            ->assertOk()
            ->assertSee('Liam Shirt Order')
            ->assertSee('3 pcs')
            ->assertSee('NT$ 900')
            ->assertSee('尚未付款')
            ->assertSee('Save Shirt Order');

        $this->actingAs($admin, 'backend')
            ->put("/backend/members/{$member->id}/class-shirt-order", [
                'items' => [
                    ['category' => 'child', 'size' => '#6', 'quantity' => 1],
                    ['category' => 'adult', 'size' => 'XL', 'quantity' => 2],
                    ['category' => 'child', 'size' => '', 'quantity' => ''],
                ],
                'payment_method' => 'transfer',
                'payment_account_last_five' => '40132',
                'payment_status' => 'completed',
            ])
            ->assertRedirect("/backend/members/{$member->id}/class-shirt-order");

        $order->refresh();

        $this->assertSame([
            ['category' => 'child', 'size' => '#6熱轉印', 'quantity' => 1],
            ['category' => 'adult', 'size' => 'XL', 'quantity' => 2],
        ], $order->items);
        $this->assertSame('transfer', $order->payment_method);
        $this->assertSame('40132', $order->payment_account_last_five);
        $this->assertSame('completed', $order->payment_status);
        $this->assertSame(900, $order->totalAmount());
        $this->assertTrue($order->submitted_at->equalTo($submittedAt));

        $this->actingAs($admin, 'backend')
            ->put("/backend/members/{$member->id}/class-shirt-order", [
                'items' => [],
            ])
            ->assertRedirect("/backend/members/{$member->id}/class-shirt-order");

        $this->assertDatabaseMissing('class_shirt_orders', [
            'id' => $order->id,
        ]);
    }

    public function test_backend_transfer_order_requires_last_five_digits(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin, 'backend')
            ->from("/backend/members/{$member->id}/class-shirt-order")
            ->put("/backend/members/{$member->id}/class-shirt-order", [
                'items' => [
                    ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
                ],
                'payment_method' => 'transfer',
                'payment_account_last_five' => '1234',
                'payment_status' => 'pending',
            ])
            ->assertRedirect("/backend/members/{$member->id}/class-shirt-order")
            ->assertSessionHasErrors('payment_account_last_five');
    }

    public function test_backend_can_save_unpaid_transfer_order_without_last_five_digits(): void
    {
        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin, 'backend')
            ->put("/backend/members/{$member->id}/class-shirt-order", [
                'items' => [
                    ['category' => 'child', 'size' => '#8', 'quantity' => 1],
                ],
                'payment_method' => 'transfer',
                'payment_account_last_five' => '',
                'payment_status' => 'unpaid',
            ])
            ->assertRedirect("/backend/members/{$member->id}/class-shirt-order")
            ->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('class_shirt_orders', [
            'user_id' => $member->id,
            'payment_method' => 'transfer',
            'payment_account_last_five' => null,
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_backend_can_export_class_shirt_orders_xlsx(): void
    {
        if (! class_exists(\ZipArchive::class)) {
            $this->markTestSkipped('The local PHP zip extension is required to generate xlsx files.');
        }

        $this->seed();
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $member = User::factory()->create([
            'name' => 'Liam',
            'is_admin' => false,
        ]);

        ClassShirtOrder::query()->create([
            'user_id' => $member->id,
            'items' => [
                ['category' => 'adult', 'size' => 'L', 'quantity' => 3],
            ],
            'submitted_at' => now(),
            'payment_method' => 'transfer',
            'payment_account_last_five' => '40132',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($admin, 'backend')
            ->get('/backend/class-shirt-orders/export')
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
