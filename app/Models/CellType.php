<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CellType extends Model
{
    protected $table = 'cell_types';

    protected $fillable = [
        'name',
    ];
}
