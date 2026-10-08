<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        "name",
        "price",
        "stock_quantity",
        "category_id",
        "supplier_id"
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
