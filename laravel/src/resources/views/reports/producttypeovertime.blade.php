@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Product Type Over Time</h1>
            <div class="col-md-12">
                <form method="post" class="form-inline">
                    {{csrf_field()}}
                    <div class="form-group">
                        <select name="producttype" class="form-control" required>
                            <option></option>
                            @foreach($producttypes as $p)
                                <option value="{{$p->id}}">{{$p->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="form-control btn btn-xs btn-primary" value="submit">
                </form>
            </div>
        </div>
        @if(isset($producttype))
            <div class="row">
                <div class="col-md-12">
                    <h2>{{$producttype->name}}</h2>
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