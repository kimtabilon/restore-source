@extends('adminlte::page')

@section('title', 'Donors')

@section('content')
<div ng-controller="donorsController">
    <section class="content-header">
        <h1>Donors <span class="badge" style="cursor: pointer;" ng-click="toggle('','create_new_donor')">Create</span></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Donors</li>
        </ol>
    </section>    

    @include('adminlte::partials.alert')

    <!-- Main content -->
    <section class="content">
        <span us-spinner="{radius:6, width:2, length:5}"></span>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
              <li ng-repeat="type in types" class="<% type.name=='Customer' ? 'active' : '' %>"><a href="#<% type.name.replace(' ', '_') %>" data-toggle="tab" aria-expanded="false"><% type.name %></a></li>
            </ul>
            <div class="tab-content">
              <div ng-repeat="type in types" class="tab-pane <% type.name=='Customer' ? 'active' : '' %>" id="<% type.name.replace(' ', '_') %>">
                <table class="table">
                  <thead>
                      <th>Items</th>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Given Name</th>
                      <th>Middle Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>Phone Number</th>
                      <th>Tel Number</th>
                      <th>Company</th>
                      <th>Job Title</th>
                      <th>Created</th>
                      <th>Store Credit</th>
                  </thead>
                  <tbody>
                      <tr ng-repeat="donor in type.donors | orderBy:'name' | filter:search">
                          <td ng-click="toggle(donor, 'show_list_of_items')"><span class="badge"><% donor.inventories.length %></span></td>
                          <td><% '00' + donor.id %></td>
                          <td><% donor.profile.title %></td>
                          <td><% donor.given_name %></td>
                          <td><% donor.middle_name %></td>
                          <td><% donor.last_name %></td>
                          <td><% donor.email %></td>
                          <td><% donor.profile.address %></td>
                          <td><% donor.profile.phone %></td>
                          <td><% donor.profile.tel %></td>
                          <td><% donor.profile.company %></td>
                          <td><% donor.profile.job_title %></td>
                          <td><% donor.created %></td>
                          <td><% donor.store_credits[0].amount %></td>
                          <td>
                              <i ng-click="toggle(donor, 'create_new_donor')" class="fa fa-edit"></i>
                              <i ng-click="remove_donor(donor)" class="fa fa-times"></i>
                          </td>
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
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="inventoryModalLabel"><% modal.title %></h4>
                </div>
                <div class="modal-body">
                    <table class="table" ng-if="modal.type=='show_list_of_items'">
                        <thead>
                            <th>Code</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Market Price</th>
                            <th>Selling Price</th>
                            <th>Remarks</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr ng-repeat="inventory in modal.inventories | orderBy:'item_status.name' | orderBy:'item_status.item.name' ">
                                <td><% code(inventory.item_codes, 'Barcode').code %></td>
                                <td><% inventory.item.name %></td>
                                <td><% inventory.quantity %></td>
                                <td><% inventory.unit %></td>

                                <td><% inventory.item_prices[ inventory.item_prices.length - 1].market_price %></td>
                                <td><% inventory.item_selling_prices[ inventory.item_selling_prices.length - 1].market_price %></td>
                                
                                <td><% inventory.remarks %></td>
                                <td><% inventory.item_status.name %></td>
                            </tr>
                        </tbody>
                    </table>


                    <form class="form-horizontal" ng-if="modal.type=='create_new_donor'">
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
                          <label class="col-sm-1 control-label">Company</label>
                          <div class="col-sm-3">
                            <input ng-model="new_customer.profile.company" type="text" class="form-control" placeholder="Company">
                          </div>
                          <label class="col-sm-1 control-label">Position</label>
                          <div class="col-sm-3">
                            <input ng-model="new_customer.profile.job_title" type="text" class="form-control" placeholder="Job Title">
                          </div>
                          <label class="col-sm-1 control-label">StrCrdt</label>
                          <div class="col-sm-3">
                            <input ng-model="new_customer.store_credits[0].amount" type="text" class="form-control" placeholder="Store Credit">
                          </div>
                        </div>  

                        <div class="form-group">
                          <label class="col-sm-1 control-label">Address</label>
                          <div class="col-sm-7">
                            <input ng-model="new_customer.profile.address" type="text" class="form-control" placeholder="Full Address">
                          </div>
                          <label class="col-sm-1 control-label">Donor</label>
                          <div class="col-sm-3">
                            <input ng-model="new_customer.donor_type" list="donor_types" type="text" class="form-control" placeholder="Donor Type">
                          </div>
                          <datalist id="donor_types">
                            <option ng-repeat="type in types" value="<% type.name %>"><% type.description %></option>
                          </datalist>
                        </div>  
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <button ng-click="new_customer_btn(new_customer, modal.title)" class="btn btn-primary btn-flat pull-right"><% modal.title %></button>
                      </div><!-- /.box-footer -->
                    </form>
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
   <script src="{{ asset('app/controllers/donors.js') }}"></script>  
@stop

        