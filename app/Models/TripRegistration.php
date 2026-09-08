<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TripRegistration extends Model
{
    use HasFactory;

    public const ROOM_DOUBLE = 'double';

    public const ROOM_QUAD = 'quad';

    public const ROOM_SIX = 'six';

    public const ROOM_TYPES = [
        self::ROOM_DOUBLE => '雙人房（一大床）',
        self::ROOM_QUAD => '四人房（兩大床）',
        self::ROOM_SIX => '六人房（三大床）',
    ];

    protected $fillable = [
        'user_id',
        'adults_count',
        'children_count',
        'child_ages',
        'room_count',
        'room_types',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'child_ages' => 'array',
            'room_types' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function roomTypeLabel(?string $type): string
    {
        return self::ROOM_TYPES[$type] ?? '-';
    }
}
