<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DateTime;

class Account extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_account';

    /**
     * Get the transactions for the account.
     */
    public function bankTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
