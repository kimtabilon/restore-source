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
        <span us-spinner="{radius:6, width:2, length:5}"></span>
        <div class="box">
            <div class="box-body">
                <table class="table">
                    <thead>
                        <th><input type="checkbox" ng-click="checkedAll()" ng-model="isAllSelected" ng-checked="countSelectedItems" style="width: 15px; height:15px;"/></th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Market Price</th>
                        <th>Discount</th>
                        <th>Barcode</th>
                        <th>Donor</th>
                        <th>Remarks</th>
                        <th>Added</th>
                    </thead>
                    <tbody ng-repeat="(key, inventory) in inventories | filter:search | groupBy:'item_id' | toArray:true | orderBy: orderByName"">
                        <tr ng-class="{active : checkParent[inventory[0].id]}">
                            <td><input type="checkbox" ng-model="checkParent[inventory[0].id]" ng-change="checked(inventory)" style="width: 15px; height:15px;" /></td>
                            <td ng-click="toggle('item', inventory[0])"><span class="badge" ng-show="inventory.length>1"><%inventory.length%></span> &nbsp;<% inventory[0].item.name %></td>
                            <td><% sum(inventory, 'quantity') %></td>
                            <td ng-click="toggle('item_price', inventory[0])"><span ng-show="inventory.length==1"><% inventory[0].item_prices[inventory[0].item_prices.length - 1].market_price %></span></td>
                            <td><span ng-show="inventory.length==1"><% (inventory[0].item_discounts | filter:{remarks:'default'})[0].percent %></span></td>
                            <td ng-click="toggle('item_code', inventory[0])"><% code(inventory[0].item.item_codes, 'Barcode').code %></td>
                            <td><span ng-show="inventory.length==1"><% inventory[0].donors[inventory[0].donors.length - 1].name %></span></td>
                            <td><span ng-show="inventory.length==1"><% inventory[0].remarks %></span></td>
                            <td><span ng-show="inventory.length==1"><% inventory[0].created %></span></td>     
                        </tr>

                        <tr ng-repeat="inv in inventory" ng-show="checkParent[inventory[0].id] && inventory.length>1" ng-class="{active : checkChild[inv.id]}">
                            <td></td>
                            <td> &nbsp;<input type="checkbox" ng-model="checkChild[inv.id]" ng-change="checked(inv)" style="width: 15px; height:15px;"/> &nbsp; <span><% inv.item.name %> </span></td>
                            <td><% inv.quantity %></td>
                            <td ng-click="toggle('item_price', inv)"><% inv.item_prices[inv.item_prices.length - 1].market_price %></td>
                            <td><% (inv.item_discounts | filter:{remarks:'default'})[0].percent %></td>
                            <td></td>
                            <td><% inv.donors[inv.donors.length - 1].name %></td>
                            <td><% inv.remarks %></td>
                            <td><% inv.created %></td>  
                        </tr>
                    </tbody>
                        
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

        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="inventoryModalLabel"><% modal.title %></h4>
                    </div>
                    <div class="modal-body">
                        <form name="items" class="form-horizontal" novalidate="" class="ng-textbox">
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
                        <button type="button" class="btn btn-primary" id="btn-save" ng-click="update(modal.data)" ng-disabled="modal.data.$invalid"><% modal.button %></button>
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
        .table>tbody+tbody {
            border-top: none; 
        }
    </style>
@stop

@section('js')
    <script src="{{ asset('app/controllers/inventories.js') }}"></script>
@stop

