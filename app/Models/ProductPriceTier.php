<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceTier extends Model
{
    public $timestamps = false;

    protected $table = 'product_price_tiers';

    protected $fillable = [
        'product_id',
        'from_quantity',
        'price',
    ];

    public function getByProductId($productId)
    {
        return $this->where('product_id', $productId)->get(['from_quantity', 'price'])->toArray();
    }
}
