<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderby('name')->get();
        $product_types = \App\ProductType::all();

        return view('products.index')->with(compact(['products','product_types']));
    }

    public function changetypes(Request $request){
        $pt = \App\ProductType::where('name','=',$request->producttype)->first();

        if(!$pt){
            $pt = \App\ProductType::create(['name'=>$request->producttype]);
        }

        $products = Product::whereIn('id',$request->products)->update(['product_type_id'=>$pt->id]);
        return redirect('/products');
    }



}
