<?php

namespace Tests\Feature;

use App\Models\ClassShirtOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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

    public function test_member_login_remember_me_sets_recaller_cookie(): void
    {
        User::factory()->create([
            'name' => 'Lily Family',
            'password' => Hash::make('member-password'),
            'is_admin' => false,
        ]);

        $this->post('/member/login', [
            'name' => 'Lily Family',
            'password' => 'member-password',
            'remember' => '1',
        ])
            ->assertRedirect('/member')
            ->assertCookie(Auth::guard('member')->getRecallerName());
    }

    public function test_member_dashboard_shows_profile_and_class_shirt_tabs(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($member, 'member')
            ->get('/member')
            ->assertOk()
            ->assertSee('家長資料')
            ->assertSee('班服訂購')
            ->assertSee('班服尺寸 - 兒童')
            ->assertSee('班服尺寸 - 成人')
            ->assertSee('Humble 班服尺寸表');
    }

    public function test_admin_cannot_login_through_member_login(): void
    {
        $this->seed();

        $this->post('/member/login', [
            'name' => 'KIWI Admin',
            'password' => 'password',
        ])->assertSessionHasErrors('name');
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

    public function test_member_can_submit_transfer_class_shirt_order_once(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($member, 'member')
            ->postJson('/member/class-shirt-order', [
                'items' => [
                    ['category' => 'child', 'size' => '#6', 'quantity' => 2],
                    ['category' => 'adult', 'size' => 'L', 'quantity' => 1],
                ],
                'payment_method' => 'transfer',
                'payment_account_last_five' => '40132',
            ])
            ->assertOk()
            ->assertJsonPath('status', '班服訂單已送出，付款待確認。')
            ->assertJsonPath('items.0.size', '#6熱轉印')
            ->assertJsonPath('payment_method_label', '匯款')
            ->assertJsonPath('payment_status_label', '付款待確認')
            ->assertJsonPath('total_quantity', 3)
            ->assertJsonPath('total_amount', 900);

        $order = ClassShirtOrder::query()->where('user_id', $member->id)->firstOrFail();

        $this->assertSame(1, ClassShirtOrder::query()->where('user_id', $member->id)->count());
        $this->assertSame([
            ['category' => 'child', 'size' => '#6熱轉印', 'quantity' => 2],
            ['category' => 'adult', 'size' => 'L', 'quantity' => 1],
        ], $order->items);
        $this->assertSame('transfer', $order->payment_method);
        $this->assertSame('40132', $order->payment_account_last_five);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame(900, $order->totalAmount());
        $this->assertNotNull($order->submitted_at);

        $this->actingAs($member, 'member')
            ->postJson('/member/class-shirt-order', [
                'items' => [
                    ['category' => 'adult', 'size' => 'XL', 'quantity' => 3],
                ],
                'payment_method' => 'cash',
            ])
            ->assertForbidden();

        $this->actingAs($member, 'member')
            ->deleteJson('/member/class-shirt-order')
            ->assertForbidden();
    }

    public function test_member_can_submit_cash_class_shirt_order_without_last_five(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($member, 'member')
            ->postJson('/member/class-shirt-order', [
                'items' => [
                    ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
                ],
                'payment_method' => 'cash',
            ])
            ->assertOk()
            ->assertJsonPath('payment_method_label', '現金')
            ->assertJsonPath('payment_account_last_five', null)
            ->assertJsonPath('payment_status_label', '付款待確認');

        $this->assertDatabaseHas('class_shirt_orders', [
            'user_id' => $member->id,
            'payment_method' => 'cash',
            'payment_account_last_five' => null,
            'payment_status' => 'pending',
        ]);
    }

    public function test_member_transfer_order_requires_last_five_digits(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($member, 'member')
            ->postJson('/member/class-shirt-order', [
                'items' => [
                    ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
                ],
                'payment_method' => 'transfer',
                'payment_account_last_five' => '1234',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('payment_account_last_five');
    }

    public function test_member_cannot_submit_invalid_class_shirt_size(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $this->actingAs($member, 'member')
            ->postJson('/member/class-shirt-order', [
                'items' => [
                    ['category' => 'child', 'size' => 'L', 'quantity' => 1],
                ],
                'payment_method' => 'cash',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('items.0.size');
    }

    public function test_member_submission_does_not_change_another_members_class_shirt_order(): void
    {
        $member = User::factory()->create(['is_admin' => false]);
        $otherMember = User::factory()->create(['is_admin' => false]);
        $otherOrder = ClassShirtOrder::query()->create([
            'user_id' => $otherMember->id,
            'items' => [
                ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
            ],
            'submitted_at' => now(),
            'payment_method' => 'cash',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($member, 'member')
            ->postJson('/member/class-shirt-order', [
                'items' => [
                    ['category' => 'adult', 'size' => 'L', 'quantity' => 2],
                ],
                'payment_method' => 'cash',
            ])
            ->assertOk();

        $otherOrder->refresh();

        $this->assertSame([
            ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
        ], $otherOrder->items);
    }

    public function test_member_dashboard_shows_submitted_order_as_read_only(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        ClassShirtOrder::query()->create([
            'user_id' => $member->id,
            'items' => [
                ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
            ],
            'submitted_at' => now(),
            'payment_method' => 'transfer',
            'payment_account_last_five' => '40132',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($member, 'member')
            ->get('/member?tab=class-shirt')
            ->assertOk()
            ->assertSee('訂單已送出，如需修改訂購內容請聯繫管理者。')
            ->assertSee('付款待確認')
            ->assertSee('40132')
            ->assertDontSee('送出班服訂單')
            ->assertDontSee('刪除');
    }

    public function test_member_can_update_submitted_order_payment_information(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $order = ClassShirtOrder::query()->create([
            'user_id' => $member->id,
            'items' => [
                ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
            ],
            'submitted_at' => now(),
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs($member, 'member')
            ->put('/member/class-shirt-order/payment', [
                'payment_method' => 'transfer',
                'payment_account_last_five' => '40132',
                'payment_status' => 'completed',
            ])
            ->assertRedirect('/member?tab=class-shirt');

        $order->refresh();

        $this->assertSame([
            ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
        ], $order->items);
        $this->assertSame('transfer', $order->payment_method);
        $this->assertSame('40132', $order->payment_account_last_five);
        $this->assertSame('unpaid', $order->payment_status);
    }

    public function test_member_cash_payment_update_clears_last_five_digits(): void
    {
        $member = User::factory()->create(['is_admin' => false]);

        $order = ClassShirtOrder::query()->create([
            'user_id' => $member->id,
            'items' => [
                ['category' => 'adult', 'size' => 'M', 'quantity' => 1],
            ],
            'submitted_at' => now(),
            'payment_method' => 'transfer',
            'payment_account_last_five' => '40132',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($member, 'member')
            ->put('/member/class-shirt-order/payment', [
                'payment_method' => 'cash',
                'payment_account_last_five' => '40132',
            ])
            ->assertRedirect('/member?tab=class-shirt');

        $order->refresh();

        $this->assertSame('cash', $order->payment_method);
        $this->assertNull($order->payment_account_last_five);
        $this->assertSame('pending', $order->payment_status);
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
