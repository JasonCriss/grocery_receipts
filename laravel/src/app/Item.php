<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function receipt()
    {
        return $this->belongsTo('App\Receipt');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
