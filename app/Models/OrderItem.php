<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $guarded = [];

    public function add($orderItem)
    {
        return OrderItem::create($orderItem);

    }
}
