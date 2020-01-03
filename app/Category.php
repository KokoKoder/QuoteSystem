<?php
  
namespace App;
  
use Illuminate\Database\Eloquent\Model;
   
class Product extends Model
{
    protected $fillable = [
        'name', 'URL','description','pictures','metaDesc','metaTitle','lang','vendor','parentCategory'
    ];
}