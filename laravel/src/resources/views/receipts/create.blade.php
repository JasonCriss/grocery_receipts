@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Add a Receipt</h1>
                <form class="form" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <input type="text" name="customer" id="customer" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="store">Store</label>
                        <select name="store" id="store" class="form-control">
                            <option>Wegmans</option>
                            <option>GreenStar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="raw">Raw</label>
                        <textarea name="raw" id="raw" rows="10" class="form-control"></textarea>
                    </div>
                    <input type="submit" class="btn btn-success btn-md">
                </form>
            </div>
        </div>
    </div>
@endsection