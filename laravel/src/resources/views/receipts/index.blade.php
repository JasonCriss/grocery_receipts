@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Store</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>$/Item</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipts as $receipt)
                            <tr>
                                <td>{{$receipt->transactiondate}}</td>
                                <td>{{$receipt->transactiontime}}</td>
                                <td>{{$receipt->store}}</td>
                                <td>${{number_format($receipt->totalsales,2)}}</td>
                                <td>{{$receipt->numitems}}</td>
                                <td>
                                    @if($receipt->numitems)
                                        {{number_format($receipt->totalsales/$receipt->numitems,2)}}
                                    @endif
                                </td>
                                <td><a href="/init/{{$receipt->id}}" target="_blank">View</a> </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection