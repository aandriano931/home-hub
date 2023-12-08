<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentCategory extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_parent_category';

    /**
     * Get the transactions for the account.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
    
}
