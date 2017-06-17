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
                            <tr ng-repeat="(key, inventory) in inventories | groupBy:'item_id'" ng-class="{active : inventory.selected}">
                                <td><input type="checkbox" ng-model="inventory[0].selected" ng-change="checked(inventory[0])" /></td>
                                <td ng-click="toggle('item', inventory[0], $index)">(<%inventory.length%>) <% inventory[0].item.name %></td>
                                <td><% sum(inventory, 'quantity') %></td>
                                <td ng-click="toggle('item_price', inventory[0], $index)"><% inventory[0].item_price.market_price %></td>
                                <td ng-click="toggle('item_code', inventory[0], $index)"><% code(inventory[0].item.item_codes, 'Barcode').code %></td>
                                <td><% inventory[0].donor.given_name %> <% inventory[0].donor.last_name %></td>
                            </tr>
                    </table>
                </div> 
                <div class="box-footer" ng-show="countSelectedItems">
                        <select style="max-width: 200px !important; font-size: 13pt; padding: 3px !important;"
                            ng-model="selectedStatus" 
                            ng-options="status.id as status.name for status in itemStatus"
                            ng-change="transfer(selectedStatus)">
                            <option 
                                ng-click="transfer(selectedStatus)"
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
        <div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="inventoryModalLabel"><% modal.title %></h4>
                    </div>
                    <div class="modal-body">
                        <form name="items" class="form-horizontal" novalidate="">
                            <div class="form-group error" ng-repeat="(field, label) in modal.field">
                                <label for="name" class="col-sm-3 control-label"><% label %></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="<% field %>" 
                                        name="<% field %>" placeholder="<% label %>" value="<% modal['data'][field] %>" 
                                        ng-model="modal['data'][field]" 
                                        ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="modal['data'][field].$invalid && modal['data'][field].$touched">
                                        <% label %> field is required</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-save" ng-click="update(modal.action, modal.data, index)" ng-disabled="modal.data.$invalid"><% modal.button %></button>
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

