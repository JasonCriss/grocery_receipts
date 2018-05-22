<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function init($id){
        $receipt = \App\Receipt::find($id);
        $your_array = explode("\r\n", $receipt->raw);
        //dd($your_array);
        return view('receipt')->with(compact(['receipt']));
        //dd($r->raw);
    }
}
