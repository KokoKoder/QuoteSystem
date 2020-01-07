<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{

    protected $fillable = [
        'product_name', 'product_URL','product_description','product_pictures','product_metaDesc','product_metaTitle','lang','vendor','product_categories','published'
    ];

}
