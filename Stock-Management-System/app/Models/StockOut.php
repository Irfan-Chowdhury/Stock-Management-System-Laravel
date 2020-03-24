<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'company_id',
        'item_id',
        'add_quantity',
        'action_no',
        'sell_quantity',
        'damage_quantity',
        'lost_quantity',
    ];
}
