<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name'
    ];

    public function items(){
        return $this->hasMany('App\Item');
    }

    public function categories(){
        return $this->belongsToMany('App\Product');
    }

    public function product_type(){
        return $this->belongsTo('App\ProductType');
    }
}
