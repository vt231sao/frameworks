<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        "customer_name",
        "status"
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
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
