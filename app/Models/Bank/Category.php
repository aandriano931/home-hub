<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_category';

    /**
     * Get the category that owns the transaction.
     */
    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(ParentCategory::class, 'bank_parent_category_id');
    }
}
