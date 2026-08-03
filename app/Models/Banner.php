<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners_p';


    function getBannersDisplay(): \Illuminate\Database\Eloquent\Collection
    {
        return Banner::all();

    }
}
