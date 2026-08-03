<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thumb extends Model
{
    protected $table = 'thumbs_p';

    public function getThumbByIds($ids)
    {
        return Thumb::whereIn('id', $ids)->get();

    }
}
