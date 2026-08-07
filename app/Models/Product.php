<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'products_p';

    protected $fillable = [
        'name',
        'image_id',
        'image_path',
        'category_id',
        'original_price',
        'sale_price',
        'type',
        'thumb_id',
        'specifications',
        'visible',
    ];

    protected $appends = [
        'discount_percent',
    ];

    protected $casts = [
        'specifications' => 'array',
        'visible' => 'boolean',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Thumb::class, 'image_id');
    }

    public function getProductsByCategory($categoryIds): array
    {
        $result = [];

        foreach ($categoryIds as $categoryId) {
            $result[$categoryId] = Product::with('image:id,src')
                ->where('visible', 1)
                ->where('category_id', $categoryId)
                ->latest()
                ->take(8)
                ->get()
                ->toArray();
        }

        return $result;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (
            empty($this->original_price) ||
            $this->original_price <= 0 ||
            empty($this->sale_price)
        ) {
            return 0;
        }

        return round(
            (1 - ($this->sale_price / $this->original_price)) * 100
        );
    }

    public function searchProducts(?string $keyword, $price, $cell, $cell_type, $category_id): AbstractPaginator|LengthAwarePaginator
    {
        //        dd($keyword, $price, $cell, $cell_type, $category_id);
        $query = Product::with('image')->where('visible', 1);
        // Search keyword

        $query->when($keyword, function ($q) use ($keyword) {

            $q->where(function ($query) use ($keyword) {

                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('original_price', 'like', "%{$keyword}%")
                    ->orWhere('sale_price', 'like', "%{$keyword}%")
                    ->orWhere('id', $keyword);
            });
        });

        // Lọc theo giá
        if ($price) {

            [$min, $max] = explode('-', $price);

            $query->where(function ($q) use ($min, $max) {

                $priceColumn = DB::raw('IF(sale_price IS NULL OR sale_price = 0, original_price, sale_price)');

                if ($max == 'max') {
                    $q->where($priceColumn, '>=', $min);
                } else {
                    $q->whereBetween($priceColumn, [$min, $max]);
                }

            });

        }

        // cell number
        if (! empty($cell)) {
            $query->whereIn('cell_number', $cell);
        }

        // type
        if (! empty($cell_type)) {
            $query->whereIn('cell_type', $cell_type);
        }

        if (! empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        //        dd($query->toSql(), $keyword, $price, $cell, $cell_type, $category_id);
        return $query->paginate(40)->withQueryString();
    }

    public function getProductById(int $id): array
    {
        return Product::with('image:id,src')
            ->where('id', $id)
            ->where('visible', 1)
            ->first()
            ->toArray();
    }
}
