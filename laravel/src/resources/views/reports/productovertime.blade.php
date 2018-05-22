@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="post" class="form-inline">
                    {{csrf_field()}}
                    <div class="form-group">
                        <select name="product" class="form-control" required>
                            <option></option>
                            @foreach($products as $p)
                                <option value="{{$p->id}}">{{$p->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="form-control btn btn-xs btn-primary" value="submit">
                </form>
            </div>
        </div>
        @if(isset($product))
            <div class="row">
                <div class="col-md-12">
                    <h1>{{$product->name}}</h1>
                    <div>{!! $chart->container() !!}</div>

                    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>--}}
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/modules/exporting.js"></script>
                     {!! $chart->script() !!}
                </div>
            </div>
        @endif
    </div>
@endsection