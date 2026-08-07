<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories_p';

    public function getCategoryLists(): Collection
    {
        return $this->all();
    }
}
