@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Items <small>{{ucwords(str_replace('-',' ',$slug))}}</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Inventory</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <table class="table">
                        <thead>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Market Price</th>
                            <th>Barcode</th>
                            <th>Donor</th>
                        </thead>
                        @foreach($inventories as $inventory)
                            <tr>
                                <td>{{$inventory->item->name}}</td>
                                <td>{{$inventory->quantity}}</td>
                                <td>{{$inventory->itemPrice->market_price}}</td>
                                <td>{{$inventory->item->itemCodes->last()->code}}</td>
                                <td>{{$inventory->donor->name}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>    

            </div>
        </div>
    </div>   
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

