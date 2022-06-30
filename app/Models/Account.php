<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property double $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Transaction incomes
 * @property Transaction payments
 * @property User user
 * @property string decrement
 */
class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'balance',
    ];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    /**
     * @return Collection
     */
    public function transactions(): Collection
    {
        return Transaction::query()->where(function ($q) {
            /** @var Builder $q */
            $q->where('payer_id', $this->id)
                ->orWhere('receiver_id', $this->id);
        })->get();
    }
}
