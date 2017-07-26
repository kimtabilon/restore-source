@extends('adminlte::page')

@section('title', 'Item Discounts')

@section('content')
<div ng-controller="discountsController">
    <section class="content-header">
        <h1>Item Discounts <span class="badge" style="cursor: pointer;" ng-click="toggle('', 'new')" >create</span></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Discounts</li>
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
                        <th>Percent</th>
                        <th>Type</th>
                        <th>Remarks</th>
                        <th>Used</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Created at</th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="discount in discounts | orderBy:'type' | filter:search">
                            <td ng-click="toggle(discount, 'edit')"><% discount.percent %></td>
                            <td><% discount.type %></td>
                            <td><% discount.remarks %></td>
                            <td><% discount.inventories.length %></td>
                            <td><span ng-if="discount.start_date!=discount.end_date"><% discount.start_date %></span></td>
                            <td><span ng-if="discount.start_date!=discount.end_date"><% discount.end_date %></span></td>
                            <td><% discount.created %></td>
                            <td><i ng-click="remove(discount)" class="fa fa-times"></i></td>
                        </tr>
                    </tbody>
                        
                </table>
            </div> 
        </div>  
    </section>
    <!-- /.content -->
    <!-- Modal (Pop up when detail button clicked) -->
    <div class="modal fade" id="itemDiscountModal" tabindex="-1" role="dialog" aria-labelledby="itemDiscountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="itemDiscountModalLabel"><% modal.title %></h4>
                </div>
                <div class="modal-body">
                    <form name="items" class="form-horizontal" novalidate="" class="ng-textbox">
                        <datalist ng-if="modal['array']['category']" id="category">
                            <option ng-repeat="data in modal['array']['category'] | orderBy:'name'" value="<% data.name %>">
                        </datalist>   
                        <div class="form-group error" ng-repeat="(field, label) in modal.field" >
                            <label for="name" class="col-sm-3 control-label"><% label %></label>
                            <div class="col-sm-9">
                                <input type="text" 
                                    class="form-control has-error" 
                                    id="<% field %>"
                                    list="<% field %>" 
                                    name="<% field %>" 
                                    placeholder="<% label %>" 
                                    ng-model="modal['data'][field]" 
                                    ng-required="true">
                                <span class="help-inline" 
                                    ng-show="modal['data'][field].$invalid && modal['data'][field].$touched">
                                    <% label %> field is required</span>    
                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary pull-right" id="btn-save" ng-click="update(modal.data)" ng-disabled="modal.data.$invalid"><% modal.button %></button>
                    <div class="clearfix"></div><br>
                    <input ng-if="modal.data.inventories.length>0" type="text" ng-model="search_items" class="form-control" placeholder="Search...">
                   
                    <table ng-if="modal.data.inventories.length>0" class="table">
                        <thead>
                            <th>Inventory</th>
                            <th>Quantity</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Added</th>
                        </thead>
                        <tr ng-repeat="inv in modal.data.inventories | orderBy:'item_status.name' | orderBy:'item.name' | filter:search_items">
                            <td><% inv.item.name %></td>
                            <td><% inv.quantity %></td>
                            <td><% inv.remarks %></td>
                            <td><% inv.item_status.name %></td>
                            <td><% inv.created %></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>    

@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
    <style>
        .pointer, td { cursor:pointer; }
        .table>tbody+tbody {
            border-top: none; 
        }
    </style>
@stop

@section('js')
   <script src="{{ asset('app/controllers/item-discounts.js') }}"></script>
@stop

        