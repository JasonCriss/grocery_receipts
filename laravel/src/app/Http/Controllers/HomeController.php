<?php

namespace App\Http\Controllers;

use App\Charts\DashboardMonthlyChart;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $start = new Carbon('first day of this month');
        $rids = \App\Receipt::where('transactiondate','>=',$start->format('Y-m-d'))->pluck('id')->toArray();

        $itemgroups = \App\Item::whereIn('receipt_id',$rids)->get()->groupBy('name');

        $items = Collect([]);

        foreach ($itemgroups as $itemgroup){
            $item = new \stdClass();
            $item->name = $itemgroup->first()->name;
            $item->cost = $itemgroup->sum('cost');
            $item->quantity = $itemgroup->sum('quantity');

            $items->push($item);
        }

        $items = $items->sortByDesc('cost');
        $top10 = collect($items->take(10)->values()->toArray());
        $othercost = $items->slice(11)->sum('cost');

        $labels = $top10->pluck('name');
        $labels->push('Other');
        $costs = $top10->pluck('cost');
        $costs->push($othercost);
        $colors = ['#a6cee3','#1f78b4','#b2df8a','#33a02c','#fb9a99','#e31a1c','#fdbf6f','#ff7f00','#cab2d6','#6a3d9a','#ffff99'];

        $chart = new DashboardMonthlyChart();
        $chart->labels($labels->toarray());
        $chart->options(['title'=>['text'=>'Top 10 Items This Month']]);
        $chart->doughnut(50);
        $chart->dataset('Cost','pie',$costs->toarray())->color($colors);

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

        $chart2 = new DashboardMonthlyChart();
        $chart2->labels($labels->toarray());
        $chart2->options(['title'=>['text'=>'Cost By Day Of Month (Current Month)']]);
        $chart2->dataset('$','column',$costs->toarray());

        $chart3 = new DashboardMonthlyChart();
        $chart3->labels($labels->toarray());
        $chart3->options(['title'=>['text'=>'Visits By Day Of Month (Current Month)']]);
        $chart3->dataset('#','column',$visits->toarray());

        return view('home')->with(compact(['chart','chart2','chart3','top10']));
    }
}