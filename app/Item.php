<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Item extends Model
{

    protected $fillable = [
        'item_name', 'supplier_sku','item_supplier_id','item_price','item_weight','item_description','item_length','item_width','item_height','package_weight','package_length','package_width','package_height','item_per_pack
    ];

}
