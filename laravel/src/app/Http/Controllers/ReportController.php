<?php

namespace App\Http\Controllers;

use App\Receipt;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Charts\DashboardMonthlyChart;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function monthlycharts(){
        $start = new Carbon('first day of this month');
        $receipts = \App\Receipt::where('transactiondate','>=',$start->format('Y-m-d'))->get()->groupBy('transactiondate');

        $dates = Collect([]);

        foreach ($receipts as $receipt){
            $receipt->weekday = (new Carbon($receipt->first()->transactiondate))->dayOfWeek;
            $date = new \stdClass();
            $date->day = (new Carbon($receipt->first()->transactiondate))->day;
            $date->transactions = $receipt->count();
            $date->cost = $receipt->sum('totalsales');
            $dates->push($date);
        }

        $month = collect([]);
        for($i=1; $i<=Carbon::now()->day; $i++){
            if(count($dates->where('day',$i))){
                $month->push($dates->where('day',$i)->first());
            }else{
                $date = new \stdClass();
                $date->day = $i;
                $date->transactions = 0;
                $date->cost = 0;
                $month->push($date);
            }
        }

        $labels = $month->pluck('day');
        $costs = $month->pluck('cost');
        $visits = $month->pluck('transactions');

        $chart = new DashboardMonthlyChart();
        $chart->labels($labels->toarray());
        $chart->options(['title'=>['text'=>'Cost By Day Of Month (Current Month)']]);
        $chart->dataset('$','column',$costs->toarray());

        $chart2 = new DashboardMonthlyChart();
        $chart2->labels($labels->toarray());
        $chart2->options(['title'=>['text'=>'Visits By Day Of Month (Current Month)']]);
        $chart2->dataset('#','column',$visits->toarray());

        //this reindexes the collection with ordered index keys
        //$top10 = $top10->values()->toArray();
        return view('reports.charts')->with(compact(['chart','chart2']));
        //dd($month);
    }

    public function productsovertime(Request $request){
        $products = \App\Product::orderBy('name')->get();
        if(isset($request->product)) {
            $product = \App\Product::find($request->product);
            $items = \App\Item::where('product_id', '=', $product->id)->get();
            $dates = \App\Receipt::find($items->unique('receipt_id')->pluck('receipt_id')->toArray())->pluck('transactiondate')->toArray();
            $startyear = intval(explode('-', min($dates))[0]);
            $endyear = intval(explode('-', max($dates))[0]);
            $startmonth = intval(explode('-', min($dates))[1]);
            $endmonth = intval(explode('-', max($dates))[1]);

            $time = collect([]);
            foreach ($items as $item) {
                $item->year = explode('-', $item->receipt->transactiondate)[0];
                $item->month = explode('-', $item->receipt->transactiondate)[1];
            }

            //dd($items->where('year',2018)->where('month','05'));

            $time = collect([]);
            for ($y = $startyear; $y <= $endyear; $y++) {
                for ($m = 1; $m <= 12; $m++) {
                    if (!(($y == $startyear && $m < $startmonth) || ($y == $endyear && $m > $endmonth))) {
                        $tmp = $items->where('year', $y)->where('month', $m);
                        $year_month = new \stdClass();
                        $year_month->title = $y . "-" . $m;
                        $year_month->quantity = $tmp->sum('quantity');
                        $year_month->cost = $tmp->sum('cost');
                        $time->push($year_month);
                    }
                }
            }

            $labels = $time->pluck('title');
            $costs = $time->pluck('cost');
            $quantity = $time->pluck('quantity');

            $chart = new DashboardMonthlyChart();
            $chart->labels($labels->toarray());
            $chart->options(['title' => ['text' => 'Cost vs Quantity Over Time']]);
            $chart->dataset('Cost', 'spline', $costs->toarray());
            $chart->dataset('Quantity', 'spline', $quantity->toarray());


            return view('reports.productovertime')->with(compact(['chart', 'product','products']));
        }else{
            return view('reports.productovertime')->with(compact(['products']));
        }

    }

    public function producttypeovertime(Request $request){
        $producttypes = \App\ProductType::orderBy('name')->get();
        if(isset($request->producttype)) {
            $producttype = \App\ProductType::find($request->producttype);
            $pids = $producttype->products->pluck('id')->toArray();
            $items = \App\Item::whereIn('product_id', $pids)->get();
            $dates = \App\Receipt::find($items->unique('receipt_id')->pluck('receipt_id')->toArray())->pluck('transactiondate')->toArray();
            $startyear = intval(explode('-', min($dates))[0]);
            $endyear = intval(explode('-', max($dates))[0]);
            $startmonth = intval(explode('-', min($dates))[1]);
            $endmonth = intval(explode('-', max($dates))[1]);

            foreach ($items as $item) {
                $item->year = explode('-', $item->receipt->transactiondate)[0];
                $item->month = explode('-', $item->receipt->transactiondate)[1];
            }

            //dd($items->where('year',2018)->where('month','05'));

            $time = collect([]);
            for ($y = $startyear; $y <= $endyear; $y++) {
                for ($m = 1; $m <= 12; $m++) {
                    if (!(($y == $startyear && $m < $startmonth) || ($y == $endyear && $m > $endmonth))) {
                        $tmp = $items->where('year', $y)->where('month', $m);
                        $year_month = new \stdClass();
                        $year_month->title = $y . "-" . $m;
                        $year_month->quantity = $tmp->sum('quantity');
                        $year_month->cost = $tmp->sum('cost');
                        $time->push($year_month);
                    }
                }
            }

            $labels = $time->pluck('title');
            $costs = $time->pluck('cost');
            $quantity = $time->pluck('quantity');

            $chart = new DashboardMonthlyChart();
            $chart->labels($labels->toarray());
            $chart->options(['title' => ['text' => 'Cost vs Quantity Over Time']]);
            $chart->dataset('Cost', 'spline', $costs->toarray());
            $chart->dataset('Quantity', 'spline', $quantity->toarray());


            return view('reports.producttypeovertime')->with(compact(['chart', 'producttype','producttypes']));
        }else{
            return view('reports.producttypeovertime')->with(compact(['producttypes']));
        }

    }

    public function costsbymonth(){
        $receipts = Receipt::all();
        $dates = $receipts->pluck('transactiondate')->toArray();
        $startyear = intval(explode('-', min($dates))[0]);
        $endyear = intval(explode('-', max($dates))[0]);
        $startmonth = intval(explode('-', min($dates))[1]);
        $endmonth = intval(explode('-', max($dates))[1]);

        foreach ($receipts as $receipt) {
            $receipt->year = explode('-',$receipt->transactiondate)[0];
            $receipt->month = explode('-', $receipt->transactiondate)[1];
        }

        $time = collect([]);
        for ($y = $startyear; $y <= $endyear; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                if (!(($y == $startyear && $m < $startmonth) || ($y == $endyear && $m > $endmonth))) {
                    $tmp = $receipts->where('year', $y)->where('month', $m);
                    $year_month = new \stdClass();
                    $year_month->title = $y . "-" . $m;
                    $year_month->quantity = $tmp->sum('numitems');
                    $year_month->cost = $tmp->sum('totalsales');
                    $time->push($year_month);
                }
            }
        }

        $labels = $time->pluck('title');
        $costs = $time->pluck('cost');
        $quantity = $time->pluck('quantity');

        $chart = new DashboardMonthlyChart();
        $chart->labels($labels->toarray());
        $chart->options(['title' => ['text' => 'Total Cost / Items by Month']]);
        $chart->dataset('Cost', 'column', $costs->toarray());
        $chart->dataset('Items', 'column', $quantity->toarray());

        return view('reports.costsbymonth')->with(compact(['chart']));
    }
}
