<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use SoftDeletes;

    public function items(){
        return $this->hasMany('App\Item');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
