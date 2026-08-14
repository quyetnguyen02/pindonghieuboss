<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductP;



class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function hukan()
    {
        return $this->belongsTo(ProductP::class, 'product_id');
    }

    public function add($orderItem)
    {
        return OrderItem::create($orderItem);
    }
}
