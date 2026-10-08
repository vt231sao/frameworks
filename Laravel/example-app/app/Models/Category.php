<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        "name",
        "description"
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    /**
     * @return HasMany
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }
}
