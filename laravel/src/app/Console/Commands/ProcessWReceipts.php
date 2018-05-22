<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessWReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:process_w';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $receipts = \App\Receipt::where('processed','=','false')->where('store','=','Wegmans')->get();
        foreach ($receipts as $receipt){
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
                $this->info("x-".$product->Name);
                $tproduct = \App\Product::where('name','=',$product->Name)->first();
                if(count($tproduct)){
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
}
