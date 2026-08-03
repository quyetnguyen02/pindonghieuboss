<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';


    function getBannersDisplay(): \Illuminate\Database\Eloquent\Collection
    {
        return Banner::all();

    }
}
