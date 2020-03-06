<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'company_id',
        'itemName',
        'reorderLevel',
    ];
}
