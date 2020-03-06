<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = [
        'company_id',
        'item_id',
        'stockIn',
    ];
}
