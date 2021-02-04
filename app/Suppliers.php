<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Suppliers extends Model
{

    protected $fillable = [
        'supplier_name', 'supplier_name','supplier_phone1','pickup_address','commercial_contract','supplier_url','standard_delivery_time',
    ];

}

//EOF
