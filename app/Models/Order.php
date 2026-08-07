<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = [];

    public const STATUS_LABELS = [
        0 => 'Chờ xác nhận',
        1 => 'Đã xác nhận',
        2 => 'Đã tạo đơn',
        3 => 'Đang giao',
        4 => 'Đang hoàn',
        5 => 'Đã nhận',
        6 => 'Đã hoàn',
        7 => 'Hủy',
    ];

    public const STATUS_CLASSES = [
        0 => 'bg-secondary',
        1 => 'bg-info text-dark',
        2 => 'bg-primary',
        3 => 'bg-warning text-dark',
        4 => 'bg-dark',
        5 => 'bg-success',
        6 => 'bg-success',
        7 => 'bg-danger',
    ];

    public const EDITABLE_ITEM_STATUSES = [0, 1, 2];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'Không xác định';
    }

    public function getStatusClassAttribute(): string
    {
        return self::STATUS_CLASSES[$this->status] ?? 'bg-secondary';
    }

    public function getCanEditItemsAttribute(): bool
    {
        return in_array($this->status, self::EDITABLE_ITEM_STATUSES, true);
    }

    public function add($order)
    {
        return Order::create($order);
    }
}
