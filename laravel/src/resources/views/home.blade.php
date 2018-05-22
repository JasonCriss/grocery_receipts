@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>{!! $chart->container() !!}</div>

                {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>--}}
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                 {!! $chart->script() !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div>{!! $chart2->container() !!}</div>

                {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>--}}
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                 {!! $chart2->script() !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div>{!! $chart3->container() !!}</div>

                {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>--}}
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                 {!! $chart3->script() !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top10 as $index=>$item)
                            <tr>
                                <td>{{$index + 1}}. {{$item->name}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>${{number_format($item->cost,2)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection