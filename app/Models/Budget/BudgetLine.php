<?php

namespace App\Models\Budget;

use App\Models\Bank\Category;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetLine extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'budget_line';

    /**
     * Get the budget that owns the budget line.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }

    /**
     * Get the category that owns the budget line.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'bank_category_id');
    }

    /**
     * Get the contributor that owns the budget line.
     */
    public function contributor(): BelongsTo
    {
        return $this->belongsTo(BudgetContributor::class, 'budget_contributor_id');
    }

}
