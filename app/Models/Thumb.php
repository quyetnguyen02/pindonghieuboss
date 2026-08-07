<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thumb extends Model
{
    protected $table = 'thumbs_p';

    protected $fillable = [
        'src',
    ];

    public function getThumbByIds($ids)
    {
        if (empty($ids)) {
            return collect();
        }

        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }

        $thumbs = Thumb::whereIn('id', $ids)->get();

        return $thumbs->sortBy(function ($model) use ($ids) {
            return array_search($model->id, $ids);
        })->values();
    }
}
