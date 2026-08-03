<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories_p';

    public function getCategoryLists(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->all();
    }

}
