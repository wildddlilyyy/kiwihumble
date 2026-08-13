<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassShirtOrder extends Model
{
    use HasFactory;

    public const UNIT_PRICE = 300;

    public const CATEGORY_CHILD = 'child';

    public const CATEGORY_ADULT = 'adult';

    public const CATEGORY_LABELS = [
        self::CATEGORY_CHILD => '兒童',
        self::CATEGORY_ADULT => '成人',
    ];

    public const SIZES = [
        self::CATEGORY_CHILD => ['#6熱轉印', '#8', '#10'],
        self::CATEGORY_ADULT => ['XS', 'S', 'M', 'L', 'XL', '2L', '3L', '5L'],
    ];

    public const PAYMENT_METHOD_CASH = 'cash';

    public const PAYMENT_METHOD_TRANSFER = 'transfer';

    public const PAYMENT_METHOD_LABELS = [
        self::PAYMENT_METHOD_CASH => '現金',
        self::PAYMENT_METHOD_TRANSFER => '匯款',
    ];

    public const PAYMENT_STATUS_UNPAID = 'unpaid';

    public const PAYMENT_STATUS_PENDING = 'pending';

    public const PAYMENT_STATUS_COMPLETED = 'completed';

    public const PAYMENT_STATUS_LABELS = [
        self::PAYMENT_STATUS_UNPAID => '尚未付款',
        self::PAYMENT_STATUS_PENDING => '付款待確認',
        self::PAYMENT_STATUS_COMPLETED => '完成',
    ];

    protected $fillable = [
        'user_id',
        'items',
        'submitted_at',
        'payment_method',
        'payment_account_last_five',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function categoryLabel(string $category): string
    {
        return self::CATEGORY_LABELS[$category] ?? $category;
    }

    public static function paymentMethodLabel(?string $method): string
    {
        return self::PAYMENT_METHOD_LABELS[$method] ?? '-';
    }

    public static function paymentStatusLabel(?string $status): string
    {
        return self::PAYMENT_STATUS_LABELS[$status ?: self::PAYMENT_STATUS_UNPAID]
            ?? self::PAYMENT_STATUS_LABELS[self::PAYMENT_STATUS_UNPAID];
    }

    public static function normalizeSize(?string $size): ?string
    {
        return $size === '#6' ? '#6熱轉印' : $size;
    }

    public function totalQuantity(): int
    {
        return collect($this->items ?? [])->sum(fn (array $item) => (int) ($item['quantity'] ?? 0));
    }

    public function totalAmount(): int
    {
        return $this->totalQuantity() * self::UNIT_PRICE;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::paymentMethodLabel($this->payment_method);
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::paymentStatusLabel($this->payment_status);
    }
}
