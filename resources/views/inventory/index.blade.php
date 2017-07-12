@extends('adminlte::page')

@section('title', 'Inventory')

@section('content')
<div ng-controller="inventoriesController">
    <section class="content-header">
        <h1>Inventory 
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
        <div class="box box-solid">
            <div class="box-body">
                <table class="table">
                    <thead>
                        <th><input type="checkbox" ng-click="checkedAll()" ng-model="isAllSelected" ng-checked="countSelectedItems" style="width: 15px; height:15px;"/></th>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Market Price</th>
                        <th>Discount</th>
                        <th>New Value</th>
                        <th>Barcode</th>
                        <th>Donor</th>
                        <th>Remarks</th>
                        <th>Added</th>
                    </thead>
                    <tbody ng-repeat="(key, inventory) in inventories | filter:search | groupBy:'item_id' | toArray:true | orderBy: orderByName"">
                        <tr ng-class="{active : checkParent[inventory[0].id]}">
                            <td><input type="checkbox" ng-model="checkParent[inventory[0].id]" ng-change="checked(inventory)" style="width: 15px; height:15px;" /></td>
                            <td class="text-center">
                                <i ng-if="inventory[0].item_images.length==0&&inventory.length==1" ng-click="display_image(inventory[0])" class="fa fa-plus"></i>
                                <img ng-if="inventory.length==1" ng-click="display_image(inventory[0])"  src="images/items/<% inventory[0].item_images[inventory[0].item_images.length-1].id %>_thumb.jpg" class="img-responsive">
                                <span class="badge" ng-if="inventory.length>1"><%inventory.length%></span></td>
                            <td ng-click="toggle('item', inventory[0])"><% inventory[0].item.name %></td>
                            <td><% sum(inventory, 'quantity') %></td>
                            <td ng-click="toggle('item_price', inventory[0])"><span ng-if="inventory.length==1"><% inventory[0].item_prices[inventory[0].item_prices.length - 1].market_price %></span></td>
                            <td ng-click="show_discounts(inventory[0])"><span ng-if="inventory.length==1"><% sum(inventory[0].item_discounts, 'percent') %></span></td>
                            <td><% new_value(inventory[0]) %></td>
                            <td ng-click="toggle('item_code', inventory[0])"><% code(inventory[0].item.item_codes, 'Barcode').code %></td>
                            <td><span ng-if="inventory.length==1"><% inventory[0].donors[inventory[0].donors.length - 1].name %></span></td>
                            <td ng-click="toggle('remarks', inventory[0])"><span ng-if="inventory.length==1"><% inventory[0].remarks %></span></td>
                            <td><span ng-if="inventory.length==1"><% inventory[0].created %></span></td>     
                        </tr>

                        <tr ng-repeat="inv in inventory" ng-if="checkParent[inventory[0].id] && inventory.length>1" ng-class="{active : checkChild[inv.id]}">
                            <td></td>
                            <td class="text-center">
                                <i ng-if="inv.item_images.length==0" ng-click="display_image(inv)" class="fa fa-plus"></i>
                                <img ng-if="inv.item_images.length>0" ng-click="display_image(inv)"  src="images/items/<% inv.item_images[inv.item_images.length-1].id %>_thumb.jpg" class="img-responsive">
                            </td>
                            <td> &nbsp;<input type="checkbox" ng-model="checkChild[inv.id]" ng-change="checked(inv)" style="width: 15px; height:15px;"/> &nbsp; <span><% inv.item.name %> </span></td>
                            <td><% inv.quantity %></td>
                            <td ng-click="toggle('item_price', inv)"><% inv.item_prices[inv.item_prices.length - 1].market_price %></td>
                            <td ng-click="show_discounts(inv)"><% sum(inv.item_discounts, 'percent') %></td>
                            <td><% new_value(inv) %></td>
                            <td></td>
                            <td><% inv.donors[inv.donors.length - 1].name %></td>
                            <td ng-click="toggle('remarks', inv)"><% inv.remarks %></td>
                            <td><% inv.created %></td>  
                        </tr>
                    </tbody>
                        
                </table>
            </div> 
            <div class="box-footer" ng-if="countSelectedItems">
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
                                        ng-if="modal['data'][field].$invalid && modal['data'][field].$touched">
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

        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="imageModalLabel"><% modal.name %> <small> - <em><% modal.remarks %></em></small></h4>
                    </div>
                    <div class="modal-body">
                        <img ng-if="modal.image!=''" src="images/items/<% modal.image %>" title="<% modal.name + ' - ' + modal.remarks %>" class="img-responsive"><br />
                        <input type="text" ng-model="searh_image" class="form-control" placeholder="Search...">
                        <br>
                        <div class="row">
                        <div class="col-xs-6 col-md-3" ng-repeat="image in itemImages | filter:searh_image | orderBy:'-name'">
                            <div class="thumbnail" title="<% image.name + ' - ' +image.description %>">
                              <img src="images/items/<% image.id %>_thumb.jpg" class="pull-left" style="height: 50px !important; margin-right: 10px;"> 
                              <button ng-click="set_image(image, modal.inventory)" class="btn btn-success btn-xs pull-right" style="margin-right: 10px;">Select</button>
                              <div class="clearfix"></div>
                              <div><% image.name | limitTo: 31 %></div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="discountModalLabel">Discount Used for <% modal.inventory.item.name %> (<% modal.inventory.quantity %>)</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <th>Percent</th>
                                <th>Type</th>
                                <th>Remarks</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Created at</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="discount in modal.discounts | orderBy:'type' | filter:search_discount">
                                    <td ng-click="toggle(discount, 'edit')"><% discount.percent %></td>
                                    <td><% discount.type %></td>
                                    <td><% discount.remarks %></td>
                                    <td><span ng-if="discount.start_date!=discount.end_date"><% discount.start_date %></span></td>
                                    <td><span ng-if="discount.start_date!=discount.end_date"><% discount.end_date %></span></td>
                                    <td><% discount.created %></td>
                                    <td><i ng-click="remove_discount(discount, modal.inventory)" class="fa fa-times"></i></td>
                                </tr>
                            </tbody>
                                
                        </table>
                        <input type="text" ng-model="search_discount" class="form-control" placeholder="Search available discounts">
                        <br>
                        <table class="table">
                            <thead>
                                <th>Percent</th>
                                <th>Type</th>
                                <th>Remarks</th>
                                <th>Used</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Created at</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="discount in itemDiscounts | orderBy:'type' | filter:search_discount">
                                    <td ng-click="toggle(discount, 'edit')"><% discount.percent %></td>
                                    <td><% discount.type %></td>
                                    <td><% discount.remarks %></td>
                                    <td><% discount.inventories.length %></td>
                                    <td><span ng-if="discount.start_date!=discount.end_date"><% discount.start_date %></span></td>
                                    <td><span ng-if="discount.start_date!=discount.end_date"><% discount.end_date %></span></td>
                                    <td><% discount.created %></td>
                                    <td><i ng-click="add_discount(discount, modal.inventory)" class="fa fa-plus"></i></td>
                                </tr>
                            </tbody>
                                
                        </table>
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

