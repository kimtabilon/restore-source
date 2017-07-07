@extends('adminlte::page')

@section('title', 'Transactions')

@section('content')
<div ng-controller="transactionsController">
    <section class="content-header">
        <h1>Trasactions <button class="btn btn-xs btn-success btn-flat" ng-click="new_transaction()">new</button></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transactions</li>
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
                        <th>Transaction#</th>
                        <th>Acknowledgement#</th>
                        <th>Customer</th>
                        <th>Payment</th>
                        <th>No. of Items</th>
                        <th>Added</th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="transaction in transactions | orderBy:'-created_at' | filter:search">
                            <td ng-click="toggle(transaction)"><% transaction.dt_number %></td>
                            <td><% transaction.da_number %></td>
                            <td><% transaction.inventories[0].donors[0].name %></td>
                            <td><% transaction.payment_type.name %></td>
                            <td><% transaction.inventories.length %></td>
                            <td><% transaction.created %></td>
                        </tr>
                    </tbody>
                        
                </table>
            </div> 
        </div>  
    </section>
    <!-- /.content -->
    <!-- Modal (Pop up when detail button clicked) -->
    <div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="inventoryModalLabel"><% modal.title %></h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <th>Item</th>
                            <th>Market Price</th>
                            <th>Quantity</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Customer</th>
                        </thead>
                        <tbody>
                            <tr ng-repeat="inventory in modal.inventories | orderBy:'item_status.name' ">
                                <td><% inventory.item.name %></td>
                                <td><% inventory.item_prices[ inventory.item_prices.length - 1].market_price %></td>
                                <td><% inventory.quantity %></td>
                                <td><% inventory.remarks %></td>
                                <td><% inventory.item_status.name %></td>
                                <td><% inventory.donors[0].name %></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal (Pop up when detail button clicked) -->
    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                          <li class=""><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">Customer</a></li>
                          <li class="active"><a href="#tab_3-2" data-toggle="tab" aria-expanded="true">Transaction</a></li>
                          <!-- <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                              Dropdown <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                              <li role="presentation" class="divider"></li>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                            </ul>
                          </li> -->
                          <li class="pull-left header"><i class="fa fa-th"></i> <% modal.title %></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane" id="tab_1-1">
                            <form class="form-horizontal">
                              <div class="box-body">
                                <div class="form-group">
                                  <label class="col-sm-1 control-label">Title</label>
                                  <div class="col-sm-1">
                                    <input ng-model="new_customer.profile.title" type="text" class="form-control" placeholder="Mr.">
                                  </div>
                                  <label class="col-sm-1 control-label">Name</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.given_name" type="text" class="form-control" placeholder="Given Name">
                                  </div>
                                  <label class="col-sm-1 control-label">MI</label>
                                  <div class="col-sm-1">
                                    <input ng-model="new_customer.middle_name" type="text" class="form-control" placeholder="MI">
                                  </div>
                                  <label class="col-sm-1 control-label">Lastname</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.last_name" type="text" class="form-control" placeholder="Lastname">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-1 control-label">Email</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.email" type="text" class="form-control" placeholder="Email">
                                  </div>

                                  <label class="col-sm-1 control-label">Phone#</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.profile.phone" type="text" class="form-control" placeholder="Phone #">
                                  </div>

                                  <label class="col-sm-1 control-label">Tel#</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.profile.tel" type="text" class="form-control" placeholder="Tel #">
                                  </div>

                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Company</label>
                                  <div class="col-sm-4">
                                    <input ng-model="new_customer.profile.company" type="text" class="form-control" placeholder="Company">
                                  </div>
                                  <label class="col-sm-2 control-label">Job Title</label>
                                  <div class="col-sm-4">
                                    <input ng-model="new_customer.profile.job_title" type="text" class="form-control" placeholder="Job Title">
                                  </div>
                                </div>  

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Full Address</label>
                                  <div class="col-sm-10">
                                    <input ng-model="new_customer.profile.address" type="text" class="form-control" placeholder="Full Address">
                                  </div>
                                </div>  

                                  
                              </div><!-- /.box-body -->
                              <div class="box-footer">
                                <button ng-click="new_customer_btn(new_customer)" class="btn btn-info pull-right">New Customer</button>
                              </div><!-- /.box-footer -->
                            </form>
                            <input type="text" ng-model="search_donor" class="form-control" placeholder="Search..">
                            <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Title</th>
                                    <th>Given Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Phone#</th>
                                    <!-- <th>Tel#</th>
                                    <th>Address</th>
                                    <th>Company</th>
                                    <th>Job Title</th> -->
                                    <th></th>
                                </tr>
                                <tr ng-repeat="donor in donors | filter:search_donor">
                                    <td><% donor.profile.title %></td>
                                    <td><% donor.given_name!=''?donor.given_name:donor.profile.company %></td>
                                    <td><% donor.middle_name %></td>
                                    <td><% donor.last_name %></td>
                                    <td><% donor.email %></td>
                                    <td><% donor.donor_type.name %></td>
                                    <td><% donor.profile.phone %></td>
                                    <!-- <td><% donor.profile.tel %></td>
                                    <td><% donor.profile.address %></td>
                                    <td><% donor.profile.company %></td>
                                    <td><% donor.profile.job_title %></td> -->
                                    <td><i ng-click="remove_donor(donor.id, $index)" class="fa fa-times"></i></td>
                                </tr>
                            </table>
                            </div>
                          </div><!-- /.tab-pane -->
                          <div class="tab-pane active" id="tab_3-2">

                            <form class="form-horizontal">
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">DT No.</label>
                                  <div class="col-sm-4">
                                    <input ng-model="transaction_no" type="text" class="form-control input-sm" placeholder="Transaction #">
                                  </div>
                                  <label class="col-sm-2 control-label">DA No.</label>
                                  <div class="col-sm-4">
                                    <input ng-model="acknowledgement_no" type="text" class="form-control input-sm" placeholder="Acknowledgement #">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Customer</label>
                                  <div class="col-sm-4">
                                    <select 
                                        class="form-control select2" 
                                        ng-model="selected_donor"
                                        ng-options="donor.id as donor.name for donor in donors | orderBy:'name'"
                                        ng-change="choose_donor(selected_donor)"
                                        style="width: 100%;">
                                    </select>
                                  </div>
                                  <label class="col-sm-2 control-label">Payment</label>
                                  <div class="col-sm-4">
                                    <select 
                                        class="form-control select2" 
                                        ng-model="selected_payment_type"
                                        ng-options="payment.id as payment.name for payment in payment_types | orderBy:'name'"
                                        ng-change="choose_payment_type(selected_payment_type)"
                                        style="width: 100%;">
                                    </select>
                                  </div>
                                </div>
                                <table class="table" ng-show="added_items.length>0">
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Remarks</th>
                                        <th></th>
                                    </tr>
                                    <tr ng-repeat="inventory in added_items track by $index">
                                        <td><% inventory.item.name %></td>
                                        <td>
                                            <input type="text" 
                                            ng-model="added_items[$index].quantity" 
                                            value="<% inventory.quantity %>"
                                            class="form-control"
                                            style="text-align:center; width: 40px;"
                                            >
                                        </td>
                                        <td>
                                            <input type="text" 
                                            ng-model="added_items[$index].remarks" 
                                            value="<% inventory.remarks %>"
                                            class="form-control"
                                            >
                                        </td>
                                        <td><i class="fa fa-times" ng-click="remove_item_from_transaction($index)"></i></td>
                                    </tr>
                                </table>

                                <button ng-click="save_transaction()" ng-disabled="added_items.length==0" type="button" class="btn btn-info pull-right" >New Transaction</button>
                                <button type="submit" class="btn btn-default pull-right" data-dismiss="modal" style="margin-right: 5px;">Close</button>
                                <div class="clearfix"></div>
                                
                                <hr />
                                <div class="form-group">
                                  <div class="col-xs-12">
                                    <input type="text" ng-model="searh_good_items" class="form-control" placeholder="Search...">
                                  </div><hr>
                                  <div class="row">
                                    <div class="col-xs-6 col-md-3" ng-repeat="inventory in inventories | filter:searh_good_items">
                                        <div class="thumbnail">
                                          <img src="<% inventory.item_images[0].name %>" alt="...">
                                          <div class="caption">
                                            Name: <br/><strong><% inventory.item.name %></strong><br/>
                                            Left: <strong><% inventory.quantity %></strong><br/>
                                            <em><% inventory.remarks %></em><br/>
                                            <p><button ng-click="cashier_add_item(inventory)" class="btn btn-primary btn-xs">Add Item</button></p>
                                          </div>
                                        </div>
                                      </div>
                                    <!-- <select 
                                        class="form-control select2" 
                                        ng-model="selected_inventory"
                                        ng-options="inventory.id as '('+inventory.quantity+') '+ inventory.item.name +' - '+ inventory.remarks for inventory in inventories | orderBy:'item.name'"
                                        ng-change="choose_item_from_inv(selected_inventory)"
                                        style="width: 100%;">
                                    </select> -->
                                  </div>
                                </div>
                            </form>
                            
                            <div class="clearfix"></div>
                          </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>    

@stop

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}"> -->
    <style>
        .pointer, td { cursor:pointer; }
        .table>tbody+tbody {
            border-top: none; 
        }
    </style>
@stop

@section('js')
   <script src="{{ asset('app/controllers/transaction.js') }}"></script>
   <script src="{{ asset('js/select2.full.min.js') }}"></script>
   <script>
      $(function () {
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        //Initialize Select2 Elements
        $(".select2").select2();
      });  
   </script>   
@stop

        