<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'budget';

    /**
     * Get the budget lines for the budget.
     */
    public function budgetLines(): HasMany
    {
        return $this->hasMany(BudgetLine::class);
    }
    
}
