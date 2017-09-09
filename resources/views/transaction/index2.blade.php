@extends('adminlte::page')

@section('title', 'Transactions')

@section('content')
<div ng-controller="transactionsController">
    <section class="content-header">
        <h1>Trasactions <span class="badge" style="cursor: pointer;" ng-click="new_transaction()">Create</span></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transactions</li>
        </ol>
    </section>    

    @include('adminlte::partials.alert')

    <!-- Main content -->
    <section class="content">
        <span us-spinner="{radius:6, width:2, length:5}"></span>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
              <li ng-repeat="type in types" class="<% type.name=='Item Donation' ? 'active' : '' %>"><a href="#<% type.name.replace(' ', '_') %>" data-toggle="tab" aria-expanded="false"><% type.name %></a></li>
            </ul>
            <div class="tab-content">
              <div ng-repeat="type in types" class="tab-pane <% type.name=='Item Donation' ? 'active' : '' %>" id="<% type.name.replace(' ', '_') %>">
                <table class="table">
                  <thead>
                      <th>DA No.</th>
                      <th>Donor/Company</th>
                      <th>No. of Items</th>
                      <th>Special Discount</th>
                      <th>Remarks</th>
                      <th>Created</th>
                  </thead>
                  <tbody>
                      <tr ng-repeat="transaction in type.transactions | orderBy:'-created_at' | filter:search">
                          <td ng-click="toggle(transaction)"><span class="badge"><% transaction.da_number %></span></td>
                          <td><% transaction.inventories[0].donors[ transaction.inventories[0].donors.length - 1 ].name %> <% transaction.inventories[0].donors[ transaction.inventories[0].donors.length - 1 ].profile.company %></td>
                          <td><% transaction.inventories.length %></td>
                          <td><% transaction.special_discount %></td>
                          <td><% transaction.remarks %></td>
                          <td><% transaction.created %></td>
                      </tr>
                  </tbody>
                      
              </table>
              </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
          </div>
        <!-- <div class="box box-solid">
            <div class="box-body">
                
            </div> 
        </div>  --> 
    </section>
    <!-- /.content -->
    <!-- Modal (Pop up when detail button clicked) -->
    <div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="PrintListOfItem">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="inventoryModalLabel"><% modal.title %></h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <th>Code</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Market Value</th>
                            <th>ReStore Value</th>
                            <!-- <th>Discount</th> -->
                            <th>Remarks</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr ng-repeat="inventory in modal.inventories">
                                <td><% code(inventory.item_codes, 'Barcode').code %></td>
                                <td><% inventory.item.name %></td>
                                <td><% inventory.quantity %></td>
                                <td><% inventory.unit %></td>

                                <td><% inventory.item_prices[ inventory.item_prices.length - 1].market_price %></td>
                                <td><% new_value(inventory) %></td>
                                <!-- <td><% sum(inventory.item_discounts, 'percent') %></td> -->
                                
                                <td><% inventory.remarks %></td>
                                <td><% inventory.item_status.name %></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                <button class="btn btn-default btn-flat" id="PrintListOfItemBtn"><i class="fa fa-print"></i> Print</button>
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
                          <li class=""><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">Donor</a></li>
                          <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">Item</a></li>
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
                                  <label class="col-sm-1 control-label">Last Name</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.last_name" type="text" class="form-control" placeholder="Last Name">
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
                                  <label class="col-sm-1 control-label">Company</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.profile.company" type="text" class="form-control" placeholder="Company">
                                  </div>
                                  <label class="col-sm-1 control-label">Position</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.profile.job_title" type="text" class="form-control" placeholder="Job Title">
                                  </div>
                                  <label class="col-sm-1 control-label">Store Credit</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.store_credits[0].amount" type="text" class="form-control" placeholder="Store Credit">
                                  </div>
                                </div>  

                                <div class="form-group">
                                  <label class="col-sm-1 control-label">Address</label>
                                  <div class="col-sm-7">
                                    <textarea ng-model="new_customer.profile.address" class="form-control"></textarea>
                                    <!-- <input ng-model="new_customer.profile.address" type="text" class="form-control" placeholder="Full Address"> -->
                                  </div>
                                  <label class="col-sm-1 control-label">Donor Type</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_customer.donor_type" list="donor_types" type="text" class="form-control" placeholder="Donor Type">
                                  </div>
                                  <datalist id="donor_types">
                                    <option ng-repeat="type in donor_types" value="<% type.name %>"><!-- <% type.description %> --></option>
                                  </datalist>
                                </div>  

                                  
                              </div><!-- /.box-body -->
                              <div class="box-footer">
                                <button ng-click="new_customer_btn(new_customer, modal.title)" class="btn btn-primary btn-flat pull-right">New Donor</button>
                              </div><!-- /.box-footer -->
                            </form>
                            <input type="text" ng-model="search_donor" class="form-control" placeholder="Search..">
                            <div class="table-responsive">
                              <table class="table">
                                <tr>
                                    <th>Type</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Items</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Phone#</th>
                                    <th>Store Credit</th>
                                    <!-- <th>Tel#</th>
                                    <th>Address</th>
                                    <th>Company</th>
                                    <th>Job Title</th> -->
                                    <!-- <th></th> -->
                                </tr>
                                <tr ng-repeat="donor in donors | orderBy:'name' | filter:search_donor">
                                    <td><% donor.donor_type.name %></td>
                                    <td><% '00'+donor.id %></td>
                                    <td><% donor.name %></td>
                                    <td><% donor.inventories.length %></td>
                                    <td><% donor.profile.company %></td>
                                    <td><% donor.email %></td>
                                    <td><% donor.profile.phone %></td>
                                    <td><% donor.store_credits[0].amount %></td>
                                    <!-- <td><% donor.profile.tel %></td>
                                    <td><% donor.profile.address %></td>
                                    <td><% donor.profile.company %></td>
                                    <td><% donor.profile.job_title %></td> -->
                                    <!-- <td><i ng-click="remove_donor(donor)" class="fa fa-times"></i></td> -->
                                </tr>
                              </table>
                            </div>
                          </div><!-- /.tab-pane -->
                          <div class="tab-pane" id="tab_2-2">
                            
                            <form class="form-horizontal">
                              <div class="box-body">

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Department</label>
                                  <div class="col-sm-3">
                                    <input ng-model="new_item.category_name" list="categories" type="text" class="form-control" placeholder="Department">
                                    <datalist id="categories">
                                        <option ng-repeat="category in categories" value="<% category.name %>"><% category.description %></option>
                                    </datalist>
                                  </div>
                                  <label class="col-sm-2 control-label">Name</label>
                                  <div class="col-sm-5">
                                    <input ng-model="new_item.name" type="text" class="form-control" placeholder="Item Name">
                                  </div>
                                  <!-- <label class="col-sm-1 control-label">Barcode</label>
                                  <div class="col-sm-2">
                                    <input ng-model="new_item.code" type="text" class="form-control" placeholder="Barcode">
                                  </div> -->
                                </div>  
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Description</label>
                                  <div class="col-sm-10">
                                    <textarea  ng-model="new_item.description" class="form-control"></textarea>
                                    <!-- <input ng-model="new_item.description" type="text" class="form-control" placeholder="Description"> -->
                                  </div>
                                </div>
                                  
                              </div><!-- /.box-body -->
                              <div class="box-footer">
                                <button ng-click="new_item_btn(new_item)" class="btn btn-primary btn-flat pull-right">New Item</button>
                              </div><!-- /.box-footer -->
                            </form>
                            <input type="text" ng-model="search_item" class="form-control" placeholder="Search..">
                            <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <!-- <th>Barcode</th> -->
                                    <th></th>
                                </tr>
                                <tr ng-repeat="item in items | filter:search_item">
                                    <td><% item.name %></td>
                                    <td><% item.description %></td>
                                    <td><% item.category.name %></td>
                                    <!-- <td><% code(item.item_codes, 'Barcode').code %></td> -->
                                    <td><i ng-click="remove_item(item.id, $index)" class="fa fa-times"></i></td>
                                </tr>
                            </table>
                            </div>


                          </div><!-- /.tab-pane -->
                          <div class="tab-pane active" id="tab_3-2">

                            <form class="form-horizontal">
                              <div id="PrintTransaction">
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">DA No.</label>
                                  <div class="col-sm-2">
                                    <input ng-model="acknowledgement_no" type="text" class="form-control input-sm" placeholder="Acknowledgement #">
                                  </div>
                                  <label class="col-sm-1 control-label">Types</label>
                                  <div class="col-sm-3">
                                    <select 
                                        class="form-control select2" 
                                        ng-model="selected_payment_type"
                                        ng-options="payment.id as payment.name for payment in types | orderBy:'name'"
                                        ng-change="choose_payment_type(selected_payment_type)"
                                        style="width: 100%;">
                                        <option>Select Payment Type</option>
                                    </select>
                                  </div>
                                  <label class="col-sm-1 control-label">Donor</label>
                                  <div class="col-sm-3">
                                    <select 
                                        class="form-control select2" 
                                        ng-model="selected_donor"
                                        ng-options="donor.id as '('+donor.store_credits[0].amount+') '+ (donor.donor_type.name=='Company' ? donor.profile.company : donor.name) + ' : ' + donor.donor_type.name for donor in donors | orderBy:'name'"
                                        ng-change="choose_donor(selected_donor)"
                                        style="width: 100%;">
                                        <option>Select Donor</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Remarks</label>
                                  <div class="col-sm-6">
                                    <textarea ng-model="remarks" class="form-control input-sm"></textarea>
                                    <!-- <input ng-model="remarks" type="text" class="form-control input-sm" placeholder="Remarks"> -->
                                  </div>
                                  <label ng-show="payment_type!='' && payment_type.name!='Item Donation'" class="col-sm-1 control-label">Discount</label>
                                  <div ng-show="payment_type!='' && payment_type.name!='Item Donation'" class="col-sm-3">
                                    <input ng-model="special_discount" type="text" class="form-control input-sm" placeholder="Special Discount">
                                  </div>
                                </div>  
                                <table class="table" ng-show="added_items.length>0">
                                    <tr>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Market Value</th>
                                        <th class="text-center">New Value</th>
                                        <th class="text-center">ReStore Value</th>
                                        <!-- <th class="text-center">Discount</th> -->
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Remarks</th>
                                        <th></th>
                                    </tr>
                                    <tr ng-repeat="inventory in added_items track by $index">
                                        <td><% code(inventory.item_codes, 'Barcode').code %></td>
                                        <td><% inventory.item.name %></td>
                                        <td>
                                            <input type="text" 
                                            ng-model="added_items[$index].quantity" 
                                            value="<% inventory.quantity %>"
                                            style="text-align:center; width: 40px; margin: 0 auto;"
                                            >
                                        </td>
                                        <td><% inventory.unit %></td>
                                        <td><% inventory.item_prices[inventory.item_prices.length-1].market_price %></td>
                                        <td><% inventory.item_selling_prices[inventory.item_selling_prices.length-1].market_price %></td>
                                        <td><% inventory.item_restore_prices[inventory.item_restore_prices.length-1].market_price %></td>
                                        <!-- <td><% new_value(inventory) %></td> -->
                                        <!-- <td><% sum(inventory.item_discounts, 'percent') %></td> -->
                                        <td><% inventory.item_status.name %></td>
                                        <td><% inventory.remarks %></td>
                                        <td><i class="fa fa-times" ng-click="remove_item_from_transaction($index)"></i></td>
                                    </tr>
                                </table>
                                <h2 class="pull-left" style="color: red; margin-top: -10px;">Total: <span><% total_transaction(added_items) %></span></h2>
                                </div>

                                <!-- <button ng-click="save_transaction()" ng-disabled="added_items.length==0" id="PrintTransactionBtn" type="button" class="btn btn-flat btn-primary pull-right" >Create Transaction</button> -->
                                <button ng-click="save_transaction()" ng-disabled="added_items.length==0" type="button" class="btn btn-flat btn-primary pull-right" >Create Transaction</button>
                                <button type="submit" class="btn btn-flat btn-default pull-right" data-dismiss="modal" style="margin-right: 5px;">Cancel</button>
                                <div class="clearfix"></div>
                                
                                <div ng-if="payment_type!='' && payment_type.name=='Item Donation'">
                                  <hr />
                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">Item</label>
                                    <div class="col-sm-4">
                                      <select 
                                          class="form-control select2" 
                                          ng-model="selected_item_from_items"
                                          ng-options="item.id as (item.name + ' - (' + item.category.name + ') ' + item.description) for item in items | orderBy:'name'"
                                          ng-change="choose_item_from_item(selected_item_from_items)"
                                          style="width: 100%;">
                                      </select>
                                    </div>
                                    <label class="col-sm-2 control-label">Item Code</label>
                                    <div class="col-sm-4">
                                      <input type="text" ng-model="new_inv.code" class="form-control input-sm" placeholder="Item Code">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">Quantity</label>
                                    <div class="col-sm-2">
                                      <input type="text" ng-model="new_inv.quantity" class="form-control input-sm" placeholder="Quantity">
                                    </div>

                                    <label class="col-sm-1 control-label">Unit</label>
                                    <div class="col-sm-2">
                                      <input type="text" ng-model="new_inv.unit" class="form-control input-sm" placeholder="Unit">
                                    </div>

                                    <label class="col-sm-1 control-label">Status</label>
                                    <div class="col-sm-4">
                                      <select 
                                          class="form-control select2" 
                                          ng-model="new_inv.status"
                                          ng-options="status.id as status.name for status in item_status | orderBy:'name'"
                                          ng-change="new_inv_status_selected(new_inv.status)"
                                          style="width: 100%;">
                                      </select>
                                    </div>

                                  </div>
                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">Market Value</label>
                                    <div class="col-sm-2">
                                      <input type="text" ng-model="new_inv.market_price" class="form-control input-sm" placeholder="Market value">
                                    </div>
                                    <label class="col-sm-2 control-label">New Value</label>
                                    <div class="col-sm-2">
                                      <input type="text" ng-model="new_inv.selling_price" class="form-control input-sm" placeholder="New Value">
                                    </div>
                                    <label class="col-sm-2 control-label">ReStore Value</label>
                                    <div class="col-sm-2">
                                      <input type="text" ng-model="new_inv.restore_price" class="form-control input-sm" placeholder="ReStore Value">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    
                                    <label class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                      <textarea ng-model="new_inv.remarks" class="form-control input-sm"></textarea>
                                      <!-- <input ng-model="new_inv.remarks" type="text" class="form-control input-sm" placeholder="Remarks"> -->
                                    </div>
                                  </div>
                                <button ng-click="add_item_to_transaction()" type="button" class="btn btn-xs btn-success pull-right" >Add Item to Transaction</button>
                                <div class="clearfix"></div>
                              </div>
                            </form>
                            <form class="form-horizontal" ng-if="payment_type!='' && payment_type.name!='Item Donation'">
                              <hr />
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Status</label>
                                  <div class="col-sm-2">
                                    <select class="form-control input-sm" 
                                        ng-model="selected_status" 
                                        ng-options="status.id as status.name + ' (' + status.inventories.length + ')' for status in item_status"
                                        ng-selected="status.id == selected_status"
                                        ng-change="inventory_status_change(selected_status)">
                                    </select>
                                  </div>
                         
                                  <label class="col-sm-2 control-label">Inventory</label>
                                  <div class="col-sm-6">
                                    <select 
                                        class="form-control select2" 
                                        ng-model="selected_inventory"
                                        ng-options="inventory.id as '('+inventory.quantity+') '+ inventory.item.name +' - '+ inventory.remarks for inventory in inventories | orderBy:'item.name'"
                                        ng-change="choose_item_from_inv(selected_inventory)"
                                        style="width: 100%;">
                                    </select>
                                  </div>
                                </div>
                                <button ng-click="add_item_to_transaction()" type="button" class="btn btn-xs btn-success pull-right" >Add Item to Transaction</button>
                                <div class="clearfix"></div>
                            </form>    
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

   <script src="{{ asset('js/printThis.js') }}"></script>
    <script type="text/javascript">
        document.getElementById("PrintListOfItemBtn").onclick = function () {
            // printElement(document.getElementById("printThis"));
            $('#PrintListOfItem').printThis();
        };
        document.getElementById("PrintTransactionBtn").onclick = function () {
            // printElement(document.getElementById("printThis"));
            $('#PrintTransaction').printThis();
        };
    </script> 
@stop

        