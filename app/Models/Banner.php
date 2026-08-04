<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners_p';

    protected $fillable = [
        'src',
    ];

    public function getBannersDisplay(): Collection
    {
        return Banner::all();

    }
}
