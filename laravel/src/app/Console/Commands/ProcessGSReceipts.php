<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessGSReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:process_gs';

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
        $receipts = \App\Receipt::where('processed','=','false')->where('store','=','GreenStar')->get();

        foreach($receipts as $receipt){

            $lines = explode("\r\n", $receipt->raw);
            $position='beforeitemlist';
            $itemquantity = 1;
            $itemname = '';
            $itemcost = 0;
            $itemdiscount = 0;
            $voided = false;
            foreach($lines as $index=>$line) {
                //$this->info($line);
                // Date and Cashier
                switch ($position){
                    case 'beforeitemlist':
                        if (starts_with($line, '#0')) {
                            $parts = explode(' ', $line);
                            //dd($items);
                            $receipt->transactiondate = $parts[1];
                            $receipt->transactiontime = $parts[2];
                            $receipt->cashier = $parts[3];
                            //echo $line."\n";
                        }
                        if (starts_with($line,'Inv#')){
                            $parts = explode(" ",$line);
                            $receipt->invoicenum =explode(":",$parts[0])[1];
                            $receipt->transactionnum =explode(":",$parts[1])[1];

                            $parts2 = explode(" ",$lines[$index +1]);
                            $receipt->customer = $parts2[0];
                            //$this->info($parts2[0]);
                        }

                        if (starts_with($line,'----------------------------------------')){
                            $position='initemlist';
                        }
                        break;
                    case 'initemlist':
                        if (starts_with($line,'----------------------------------------')){
                            if($itemname!=''){
                                if(!$voided){
                                    $item = new \App\Item();
                                    $product = \App\Product::where('name','=',$itemname)->first();
                                    if($product){
                                        $item->product_id = $product->id;
                                    }else{
                                        $newproduct = \App\Product::create(['name'=>$itemname]);
                                        $item->product_id = $newproduct->id;
                                    }
                                    $item->name = $itemname;
                                    $item->quantity = $itemquantity;
                                    $item->cost = $itemcost;
                                    $item->memberdiscount = $itemdiscount;
                                    $item->receipt()->associate($receipt);
                                    $item->save();
                                }else {
                                    $voided = false;
                                }
                            }
                            $position='insales';
                        }else {
                            if(str_contains($line, '** Voided')){
                                $voided = true;
                            }elseif (str_contains($line, '@')) {
                                if($itemname != ''){

                                    if(!$voided){
                                        $item = new \App\Item();
                                        $product = \App\Product::where('name','=',$itemname)->first();
                                        if($product){
                                            $item->product_id = $product->id;
                                        }else{
                                            $newproduct = \App\Product::create(['name'=>$itemname]);
                                            $item->product_id = $newproduct->id;
                                        }
                                        $item->name = $itemname;
                                        $item->quantity = $itemquantity;
                                        $item->cost = $itemcost;
                                        $item->memberdiscount = $itemdiscount;
                                        $item->receipt()->associate($receipt);
                                        $item->save();
                                    }else {
                                        $voided = false;
                                    }

                                    $itemquantity = 1;
                                    $itemname = '';
                                    $itemcost = 0;
                                    $itemdiscount = 0;
                                }if($itemname != ''){
                                    if(!$voided){
                                        $item = new \App\Item();
                                        $product = \App\Product::where('name','=',$itemname)->first();
                                        if($product){
                                            $item->product_id = $product->id;
                                        }else{
                                            $newproduct = \App\Product::create(['name'=>$itemname]);
                                            $item->product_id = $newproduct->id;
                                        }
                                        $item->name = $itemname;
                                        $item->quantity = $itemquantity;
                                        $item->cost = $itemcost;
                                        $item->memberdiscount = $itemdiscount;
                                        $item->receipt()->associate($receipt);
                                        $item->save();
                                    }else {
                                        $voided = false;
                                    }

                                    $itemquantity = 1;
                                    $itemname = '';
                                    $itemcost = 0;
                                    $itemdiscount = 0;
                                }else{
                                    $itemquantity = explode(' ', $line)[0];
                                }
                            } elseif (starts_with($line, 'Member Discount:')) {
                                $itemdiscount = trim(explode('$', $line)[1]);

                                if(!$voided){
                                    $item = new \App\Item();
                                    $product = \App\Product::where('name','=',$itemname)->first();
                                    if($product){
                                        $item->product_id = $product->id;
                                    }else{
                                        $newproduct = \App\Product::create(['name'=>$itemname]);
                                        $item->product_id = $newproduct->id;
                                    }
                                    $item->name = $itemname;
                                    $item->quantity = $itemquantity;
                                    $item->cost = $itemcost;
                                    $item->memberdiscount = $itemdiscount;
                                    $item->receipt()->associate($receipt);
                                    $item->save();
                                }else {
                                    $voided = false;
                                }

                                $itemquantity = 1;
                                $itemname = '';
                                $itemcost = 0;
                                $itemdiscount = 0;
                            } elseif (starts_with($line, '+Bottle') || starts_with($line, 'CPN') || starts_with($line, '-Vendor')||starts_with($line, '**')) {
                                if($itemname != ''){
                                    if(!$voided){
                                        $item = new \App\Item();
                                        $product = \App\Product::where('name','=',$itemname)->first();
                                        if($product){
                                            $item->product_id = $product->id;
                                        }else{
                                            $newproduct = \App\Product::create(['name'=>$itemname]);
                                            $item->product_id = $newproduct->id;
                                        }
                                        $item->name = $itemname;
                                        $item->quantity = $itemquantity;
                                        $item->cost = $itemcost;
                                        $item->memberdiscount = $itemdiscount;
                                        $item->receipt()->associate($receipt);
                                        $item->save();
                                    }else {
                                        $voided = false;
                                    }

                                    $itemquantity = 1;
                                    $itemname = '';
                                    $itemcost = 0;
                                    $itemdiscount = 0;
                                }
                            } else {
                                if ($itemname == '') {
                                    //echo $line;
                                    $itemname = trim(explode('$', $line)[0]);
                                    $itemcost = trim(explode(' ',explode('$', $line)[1])[0]);

                                }else{
                                    if(!$voided){
                                        $item = new \App\Item();
                                        $product = \App\Product::where('name','=',$itemname)->first();
                                        if($product){
                                            $item->product_id = $product->id;
                                        }else{
                                            $newproduct = \App\Product::create(['name'=>$itemname]);
                                            $item->product_id = $newproduct->id;
                                        }
                                        $item->name = $itemname;
                                        $item->quantity = $itemquantity;
                                        $item->cost = $itemcost;
                                        $item->memberdiscount = $itemdiscount;
                                        $item->receipt()->associate($receipt);
                                        $item->save();
                                    }else {
                                        $voided = false;
                                    }

                                    $itemquantity = 1;
                                    $itemname = '';
                                    $itemcost = 0;
                                    $itemdiscount = 0;

                                    $itemname = trim(explode('$', $line)[0]);
                                    $itemcost = trim(explode(' ',explode('$', $line)[1])[0]);
                                }
                            }

                        }
                        break;
                    case 'insales':
                        if (starts_with($line,'----------------------------------------')){
                            $position='inpaymentmethod';
                        }
                        if (starts_with($line, 'Net Sales')) {
                            $parts = explode('$', $line);
                            $receipt->netsales=trim($parts[1]);
                        }
                        if (starts_with($line, 'Tax')) {
                            $parts = explode(' $', $line);
                            $receipt->tax=trim($parts[1]);
                        }
                        if (starts_with($line, 'TOTAL SALES')) {
                            $parts = explode(' $', $line);
                            $receipt->totalsales=trim($parts[1]);
                        }
                        break;
                    case 'inpaymentmethod':
                        if (starts_with($line,'----------------------------------------')){
                            $position='inbreakdown';
                        }
                        if (starts_with($line,'Cash')){
                            $receipt->paymentmethod = 'cash';
                        }
                        break;
                    case 'inbreakdown':
                        if (starts_with($line,'<div')){
                            $position='postreceipt';
                        }
                        if (starts_with($line, 'Item count')) {
                            $parts = preg_split('/\s+/', $line);
                            //dd($parts);
                            $receipt->numitems=trim($parts[2]);
                        }
                        if (starts_with($line, 'Member Discount')) {
                            $parts = explode(' $', $line);
                            $receipt->memberdiscount=trim($parts[1]);
                        }

                        break;

                }
            }
            $receipt->processed = true;
            $receipt->save();

        }
    }
}
