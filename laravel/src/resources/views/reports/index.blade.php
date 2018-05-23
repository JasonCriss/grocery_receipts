@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="/reports/productsovertime">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Products Over Time</h3>
                        </div>
                        <div class="panel-body">
                            Choose individual products and see how much was spent over time.
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/reports/producttypeovertime">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Products Types Over Time</h3>
                        </div>
                        <div class="panel-body">
                            Choose individual products types (grouped products) and see how much was spent over time.
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/reports/costsbymonth">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Costs By Month</h3>
                        </div>
                        <div class="panel-body">
                            View total cost by month
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection