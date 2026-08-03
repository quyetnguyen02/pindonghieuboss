<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceTier extends Model
{
    protected $table = 'product_price_tiers';

    public function getByProductId($productId)
    {
        return $this->where('product_id', $productId)->get(['from_quantity', 'price'])->toArray();

    }
}
