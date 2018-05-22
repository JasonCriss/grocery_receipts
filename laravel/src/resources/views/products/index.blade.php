@extends('layouts.app')

@section('content')
    <script>
        $(function () {

            $("#myTable").tablesorter({
                theme: "bootstrap",

                widthFixed: true,

                // widget code contained in the jquery.tablesorter.widgets.js file
                // use the zebra stripe widget if you plan on hiding any rows (filter widget)
                // the uitheme widget is NOT REQUIRED!
                widgets: ["filter", "columns", "zebra"],

                widgetOptions: {
                    // using the default zebra striping class name, so it actually isn't included in the theme variable above
                    // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
                    zebra: ["even", "odd"],

                    // class names added to columns when sorted
                    columns: ["primary", "secondary", "tertiary"],

                    // reset filters button
                    filter_reset: ".reset",

                    // extra css class name (string or array) added to the filter element (input or select)
                    filter_cssFilter: [
                        'form-control',
                        'form-control',
                        'form-control custom-select', // select needs custom class names :(
                        'form-control',
                        'form-control',
                        'form-control',
                        'form-control'
                    ]

                }
            })


        });
    </script>
    <form name="form" method="post" action="">
        {{csrf_field()}}
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <table class="table tablesorter" id="myTable">
                        <thead>
                        <tr>
                            <th>Names - {{count($products)}}</th>
                            <th>Product Type</th>
                            <th data-filter="false"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" name="products[]" value="{{$product->id}}">
                                    {{$product->name}}
                                </td>
                                <td>
                                    @if($product->product_type)
                                        {{$product->product_type->name}}
                                    @endif
                                </td>
                                <td><a href="" class="btn btn-sm btn-primary">Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="sticky-top py-3 bg-light" id="sidebar">
                        <label for="producttype">Add To Product Type</label>
                        <input class="form-control" type="text" name="producttype" id="producttype" required>
                        <input type="submit" value="submit" name="submit">
                    </div>
                    <div>
                        Click item to fill in field above.
                        <ul>
                            @foreach($product_types->sortBy('name') as $product_type)
                                <li onClick="$('#producttype').val('{{$product_type->name}}');">{{$product_type->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection