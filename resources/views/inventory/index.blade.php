@extends('adminlte::page')

<!-- @section('title', 'Dashboard') -->

@section('content')
<div ng-controller="inventoriesController">
<section class="content-header">
    <h1>Inventories 
        <span class="label label-default"><% status.replace('-', ' ') | camelCase %> Items</span>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Inventory</li>
    </ol>
</section>    

@include('adminlte::partials.alert')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <th><input type="checkbox" ng-click="checkedAll()" ng-model="isAllSelected" ng-checked="countSelectedItems" /></th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Market Price</th>
                            <th>Barcode</th>
                            <th>Donor</th>
                        </thead>
                            <tr ng-repeat="inventory in inventories | filter:search" ng-class="{active : inventory.selected}">
                                <td><input type="checkbox" ng-model="inventory.selected" ng-change="checked(inventory)" /></td>
                                <td ng-click="toggle('item', inventory, $index)"><% inventory.item.name %></td>
                                <td><% inventory.quantity %></td>
                                <td><% inventory.item_price.market_price %></td>
                                <td><% inventory.item.item_codes[0].code %></td>
                                <td><% inventory.donor.given_name %> <% inventory.donor.last_name %></td>
                            </tr>
                    </table>
                </div> 
                <div class="box-footer" ng-show="countSelectedItems">
                    <select style="max-width: 200px !important; font-size: 13pt; padding: 3px !important;"
                        ng-model="selectedStatus" 
                        ng-options="status.id as status.name for status in itemStatus"
                        ng-change="transfer(selectedStatus)">
                        <option 
                            ng-selected="countSelectedItems==0"
                            ng-pluralize
                            count="countSelectedItems"
                            when="{'0': 'No selected item',
                                   'one': 'Move item',
                                   'other': 'Move {} items'}">
                        </option>
                    </select>
                </div>   

            </div>
        </div>

        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="editItemModalLabel"><% form_title %></h4>
                    </div>
                    <div class="modal-body">
                        <form name="frmEmployees" class="form-horizontal" novalidate="">
                            <div class="form-group error">
                                <label for="name" class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="name" name="name" placeholder="Item Name" value="<% data.name %>" 
                                    ng-model="data.name" ng-required="true">
                                    <span class="help-inline" 
                                    ng-show="data.name.$invalid && data.name.$touched">Item name field is required</span>
                                </div>
                            </div>

                            <div class="form-group error">
                                <label for="description" class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="description" name="description" placeholder="Description" value="<% data.description %>" 
                                    ng-model="data.description" ng-required="true">
                                    <span class="help-inline" 
                                    ng-show="data.description.$invalid && data.description.$touched">Description is required</span>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-save" ng-click="update(type, data, index)" ng-disabled="frmItems.$invalid">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>
<!-- /.content -->
</div>    

@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
    <style>
        td { cursor:pointer; }
    </style>
@stop

@section('js')
    <script src="{{ asset('app/controllers/inventories.js') }}"></script>
    <script type="text/javascript">
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
    </script>
@stop

