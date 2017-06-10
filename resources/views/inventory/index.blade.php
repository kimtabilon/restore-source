@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Inventories <span class="label label-default">{{ucwords(str_replace('-',' ',$slug))}} Items</span></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Inventory</li>
    </ol>
@stop

@section('content')
    <div class="row" ng-controller="inventoriesController" ng-init="init('{{$slug}}')">
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
                            <tr ng-repeat="inventory in inventories">
                                <td><% inventory.item.name %></td>
                                <td><% inventory.quantity %></td>
                                <td><% inventory.item_price.market_price %></td>
                                <td><% inventory.item.item_codes[0].code %></td>
                                <td><% inventory.donor.given_name %> <% inventory.donor.last_name %></td>
                            </tr>
                    </table>
                </div>    

            </div>
        </div>
    </div>   
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
    <script src="{{ asset('app/controllers/inventories.js') }}"></script>
@stop

