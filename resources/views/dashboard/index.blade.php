@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div ng-controller="dashboardController" class="print-page">
    <section class="content-header">
        <h1 class="pull-left">Report</h1>
        <div class="input-group pull-left" style="width: 200px !important; margin: -3px 5px 0 15px;">
          <span class="input-group-addon" id="basic-addon1">From</span>
          <input ng-model="report.from" value="<% report.from %>" ng-change="show_report()" class="from" placeholder="04303" type="date" class="form-control" aria-describedby="basic-addon1">
        </div>
        <div class="input-group pull-left" style="width: 200px !important; margin: -3px 5px 0 5px;">
          <span class="input-group-addon" id="basic-addon1">To</span>
          <input ng-model="report.to" value="<% report.to %>" ng-change="show_report()" class="to" type="date" class="form-control" aria-describedby="basic-addon1">
        </div>
        <div class="pull-left" style="width: 200px !important; margin: 0px 5px 0 15px;">
          <span class="fa fa-2x fa-print" style="cursor: pointer;"></span>
        </div>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
        <div class="clearfix"></div>
    </section>    

    @include('adminlte::partials.alert')

    <!-- Main content -->
    <section class="content">
        <span us-spinner="{radius:6, width:2, length:5}"></span>
         <!-- Info boxes -->
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-list-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Floor Inventory</span>
                  <span class="info-box-number"><% good_items.inventories.length %></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-share-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Transactions</span>
                  <span class="info-box-number"><% transactions.length %></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Sold</span>
                  <span class="info-box-number"><% sold_items.inventories.length %></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Donors</span>
                  <span class="info-box-number"><% donors.length %></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

    

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <!-- MAP & BOX PANE -->
              
              <div class="row">

                <div class="col-md-6">
                  <div class="box box-default">
                    <div class="box-header with-border">
                      <h3 class="box-title">Item Status</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="chart-responsive">
                            <canvas id="pieChart" height="150"></canvas>
                          </div><!-- ./chart-responsive -->
                        </div><!-- /.col -->
                      </div><!-- /.row -->
                    </div><!-- /.box-body -->
                    <div class="box-footer no-padding">
                      <ul class="nav nav-pills nav-stacked">
                        <li ng-repeat="status in itemStatus"><a href="#"> <i class="fa fa-fw fa-circle-o" style="color: <% itemStatusColors[$index] %>"></i> <% status.name %> <span class="pull-right" style="color: <% itemStatusColors[$index] %>"><% status.inventories.length %></span></a></li>
                      </ul>
                    </div><!-- /.footer -->
                  </div><!-- /.box -->
                  
                </div><!-- /.col -->

                <div class="col-sm-6 col-xs-12">
                  <!-- PRODUCT LIST -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Recently Added to Inventory</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <ul class="products-list product-list-in-box">
                    <li class="item" ng-repeat="inventory in inventories">
                      <div class="product-img">
                        <img src="{{ asset('images/items/<% inventory.item_images[inventory.item_images.length - 1].id %>_thumb.jpg') }}" alt="Product Image">
                      </div>
                      <div class="product-info">
                        <a href="javascript::;" class="product-title"><% inventory.item.name | limitTo:20 %> (<% inventory.quantity %>) <span class="label label-info pull-right"><% inventory.item_prices[0].market_price %></span></a>
                        <span class="product-description">
                          (<% inventory.item_status.name %>) <% inventory.remarks %>
                        </span>
                      </div>
                    </li><!-- /.item -->
                    
                  </ul>
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="inventories#good" target="_self" class="uppercase">View Inventory</a>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
                </div>
              </div><!-- /.row -->
            </div><!-- /.col -->

            <div class="col-md-4 col-xs-12">
              <!-- Info Boxes Style 2 -->
              <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Inventory</span>
                  <span class="info-box-number"><% inventories.length %></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 50%"></div>
                  </div>
                  <span class="progress-description">
                    <!-- 50% Increase in 30 Days -->
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Donors</span>
                  <span class="info-box-number"><% donors.length %></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 20%"></div>
                  </div>
                  <span class="progress-description">
                    <!-- 20% Increase in 30 Days -->
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Items</span>
                  <span class="info-box-number"><% items.length %></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                  </div>
                  <span class="progress-description">
                    <!-- 70% Increase in 30 Days -->
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Sold</span>
                  <span class="info-box-number"><% sold_items.inventories.length %></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 40%"></div>
                  </div>
                  <span class="progress-description">
                    <!-- 40% Increase in 30 Days -->
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->

              <!-- USERS LIST -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Top Donors</h3>
                  <div class="box-tools pull-right">
                    <span class="label label-danger"><% donors.length %> Top Donors</span>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                    <li ng-repeat="donor in donors">
                      <img src="/vendor/adminlte/dist/img/user1-128x128.jpg" alt="User Image">
                      <a class="users-list-name" href="#"><% donor.name %></a>
                      <span class="users-list-date"><% donor.created %></span>
                    </li>
                    
                  </ul><!-- /.users-list -->
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="donors" target="_self" class="uppercase">View All Donors</a>
                </div><!-- /.box-footer -->
              </div><!--/.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
          <div class="row">
          <div class="col-xs-12">
            <!-- TABLE: LATEST ORDERS -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Items</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                        <tr>
                          <th>Item</th>
                          <th>Description</th>
                          <th>Created</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr ng-repeat="item in items">
                          <td><% item.name %></td>
                          <td><% item.description %></td>
                          <td><% item.created %></td>
                        </tr>
                      </tbody>
                    </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                  <a href="items" target="_self" class="btn btn-sm btn-default btn-flat pull-right">View All Items</a>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
          </div>
          </div>
    </section>
    <!-- /.content -->
    
</div>    

@stop

@section('css')
  <style>
        td { cursor:pointer; }
        .table>tbody+tbody {
            border-top: none; 
        }

        @media screen {

        }
        @media print {            
            .btn, a, a.btn {
              display: none;
            }
        }

    </style>
@stop

@section('js')
   <script src="{{ asset('js/Chart.min.js') }}"></script>
   <script src="{{ asset('js/dashboard2.js') }}"></script>
   <script src="{{ asset('app/controllers/dashboards.js') }}"></script>
   <script src="{{ asset('js/printThis.js') }}"></script>
    <script type="text/javascript">
        $('.fa-print').on('click', function() {
          $('.print-page').printThis({
              debug: true,               // show the iframe for debugging
              importCSS: true,            // import page CSS
              importStyle: true,         // import style tags
              printContainer: true,       // grab outer container as well as the contents of the selector
              loadCSS: ["{{ asset('vendor/adminlte/bootstrap/css/bootstrap.min.css') }}", "{{ asset('css/font-awesome.min.css') }}", "{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}"],  // path to additional css file - use an array [] for multiple
              pageTitle: "Summary Report for "+ $('.from').val() +" to "+ $('.to').val(),              // add title to print page
              removeInline: false,        // remove all inline styles from print elements
              printDelay: 333,            // variable print delay; depending on complexity a higher value may be necessary
              header: null,               // prefix to html
              footer: null,               // postfix to html
              base: false ,               // preserve the BASE tag, or accept a string for the URL
              formValues: true,           // preserve input/form values
              canvas: true,              // copy canvas elements (experimental)
              doctypeString: "...",       // enter a different doctype for older markup
              removeScripts: false        // remove script tags from print content
          });
        });
    </script>
@stop

        