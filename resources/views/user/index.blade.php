@extends('adminlte::page')

@section('title', 'Users')

@section('content')
<div ng-controller="usersController">
    <section class="content-header">
        <h1>Users &nbsp; <button ng-click="toggle('', 'new')" class="btn btn-info btn-xs">new</button></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
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
                        <th>Given Name</th>
                        <th>MI</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created at</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="user in users | orderBy:'role_id' | filter:search">
                            <td ng-click="toggle(user, 'edit')"><% user.given_name %></td>
                            <td><% user.middle_name %></td>
                            <td><% user.last_name %></td>
                            <td><% user.username %></td>
                            <td><% user.email %></td>
                            <td><% user.role.name %></td>
                            <td><% user.created_at %></td>
                            <td><i ng-click="remove(user)" class="fa fa-times"></i></td>
                        </tr>
                    </tbody>
                        
                </table>
            </div> 
        </div>  
    </section>
    <!-- /.content -->
    <!-- Modal (Pop up when detail button clicked) -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="userModalLabel"><% modal.title %></h4>
                </div>
                <div class="modal-body">
                    <form name="items" class="form-horizontal" novalidate="" class="ng-textbox">
                        <datalist ng-if="modal['array']['role']" id="role">
                            <option ng-repeat="data in modal['array']['role']" value="<% data.name %>">
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
   <script src="{{ asset('app/controllers/users.js') }}"></script>
@stop

        