<?php

namespace App\Models;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $status
 * @property DateTimeImmutable $possible_widthdraw_at
 */
class Trade extends Model
{
    use HasFactory;

    public const STATUS_PURCHASED = 'purchased';
    public const STATUS_STORAGE = 'storage';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_WITHDRAWN = 'withdrawn';
    public const STATUS_ERROR = 'error';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PURCHASED,
        self::STATUS_STORAGE,
        self::STATUS_PENDING,
        self::STATUS_PROCESSING,
        self::STATUS_WITHDRAWN,
        self::STATUS_ERROR,
        self::STATUS_CANCELLED,
    ];

    protected $table = 'product_user';

    protected $fillable = [
        'status',
        'possible_widthdraw_at',
        'bot_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
