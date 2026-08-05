<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassShirtOrder extends Model
{
    use HasFactory;

    public const CATEGORY_CHILD = 'child';

    public const CATEGORY_ADULT = 'adult';

    public const CATEGORY_LABELS = [
        self::CATEGORY_CHILD => '兒童',
        self::CATEGORY_ADULT => '大人',
    ];

    public const SIZES = [
        self::CATEGORY_CHILD => ['#6', '#8', '#10'],
        self::CATEGORY_ADULT => ['XS', 'S', 'M', 'L', 'XL', '2L', '3L', '5L'],
    ];

    protected $fillable = [
        'user_id',
        'category',
        'size',
        'quantity',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categoryLabel(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? $this->category;
    }
}
