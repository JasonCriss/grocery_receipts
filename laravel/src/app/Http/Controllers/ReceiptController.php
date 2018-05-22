<?php

namespace App\Http\Controllers;

use App\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function processreceipt($receipt){
        if($receipt->store == 'Wegmans'){
            $temp = json_decode($receipt->raw);
            $datetime = explode('T',$temp->TransactionDate);
            $receipt->transactiondate = $datetime[0];
            $receipt->transactiontime = explode('+',$datetime[1])[0];
            $receipt->customer="Jason";
            $receipt->totalsales = $temp->NetSaleAmount;
            $receipt->cashier = $temp->OperatorId;
            $receipt->transactionnum = $temp->TransactionNumber;
            $receipt->tax = $temp->TaxAmount;
            foreach($temp->Products as $product){
                $item = new \App\Item();
                $tproduct = \App\Product::where('name','=',$product->Name)->first();
                if($tproduct){
                    $item->product_id = $tproduct->id;
                }else{
                    $newproduct = \App\Product::create(['name'=>$product->Name]);
                    $item->product_id = $newproduct->id;
                }
                $item->name = $product->Name;
                $item->quantity = $product->Quantity;
                $item->cost = $product->PricePaid;
                $item->receipt_id = $receipt->id;
                $item->save();
            }
            $receipt->processed = true;
            $receipt->save();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receipts = Receipt::all()->sortByDesc('transactiondate');

        return view('receipts.index')->with(compact(['receipts']));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('receipts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $receipt = new \App\Receipt();
        $receipt->raw = $request->raw;
        $receipt->store = $request->store;
        $receipt->customer = $request->customer;
        $receipt->save();
        $this->processreceipt($receipt);
        return redirect('/receipts');
    }

}
