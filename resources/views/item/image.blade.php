@extends('adminlte::page')

@section('title', 'Items')

@section('content')

<div ng-controller="itemImagesController">
    <section class="content-header">
        <h1>Item Image</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Items</li>
        </ol>
    </section>    

    @include('adminlte::partials.alert')

    <!-- Main content -->
    <section class="content">
        <span us-spinner="{radius:6, width:2, length:5}"></span>
        <div class="box box-solid">
            <div class="box-body">
            <div class="row">
                <div class="col-md-12 form-horizontal">
                    <div class="form-group">
                      <label class="col-sm-1 control-label">Name</label>
                      <div class="col-sm-2">
                        <input ng-model="image.name" type="text" class="form-control" placeholder="Name">
                      </div>

                      <label class="col-sm-1 control-label">Description</label>
                      <div class="col-sm-3">
                        <input ng-model="image.description" type="text" class="form-control" placeholder="Description">
                      </div>

                      <div class="col-sm-3">
                        <input type="file" ng-files="setTheFiles($files)" id="image_file"  class="form-control">
                      </div>

                      <div class="col-sm-2">
                        <button ng-click="uploadFile()" class="btn btn-primary">Upload</button>
                      </div>
                    </div>      
     
                    <ul class="alert alert-danger alert-sm" ng-if="errors.length > 0">
                        <li ng-repeat="error in errors">
                            <% error %>
                        </li>
                    </ul>
                </div>
            </div>
     
            <div class="row">
                <div class="col-md-12">
                    <table ng-if="files.length > 0" class="table table-bordered table-striped">
                        <tr>
                            <th>No.</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Size</th>
                            <th>Type</th>
                            <th>Uploaded</th>
                            <th></th>
                        </tr>
                        <tr ng-repeat="file in files | filter:search">
                            <td><% $index + 1 %></td>
                            <td>
                                <img src="{{asset('images/items/<% file.id %>_thumb.jpg')}}" ng-click="display_image(file)" class="img-responsive">
                            </td>
                            <td><% file.name %></td>
                            <td><% file.description %></td>
                            <td><% file.size %></td>
                            <td><% file.type %></td>
                            <th><% file.created %></th>
                            <td>
                                <i ng-click="deleteFile(file.id, $index)" class="fa fa-times"></i>
                            </td>
                        </tr>
                    </table>
                    <div class="alert alert-success alert-sm" ng-if="files.length == 0">
                        No image found. Please upload new image.
                    </div>
                </div>
            </div>
            </div>
        </div>  
    </section>
    <!-- /.content -->

    <!-- Modal (Pop up when detail button clicked) -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="imageModalLabel"><% modal.name %> <small> - <em><% modal.description %></em></small></h4>
                </div>
                <div class="modal-body">
                    <img src="{{asset('images/items/<% modal.image %>')}}" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
    
</div>    

@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
   <script src="{{ asset('app/controllers/item-images.js') }}"></script>
@stop

        