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

    public function item()  
    {
        return $this->belongsTo(Item::class);
        // return $this->belongsTo(Item::class,'item_id'); //or you can write this line
    }
    
    public function company()  
    {
        return $this->belongsTo(Company::class);
    }
}
