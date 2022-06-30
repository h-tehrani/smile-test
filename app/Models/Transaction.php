<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $payer_id
 * @property int $receiver_id
 * @property double $amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Account payer
 * @property Account receiver
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'payer_id',
        'receiver_id',
        'amount',
    ];

    /**
     * @return BelongsTo
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'payer_id');
    }

    /**
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'receiver_id');
    }
}
