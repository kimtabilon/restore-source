@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @if (Auth::check())
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                @endif
            </div>
        @endif

        <div class="content">
            <div class="title m-b-md">
                ReStore Inventory
            </div>

            <div class="links">
                @foreach ($status as $s)
                    <a href="{{ url('items') }}/{{$s->id}}" class="{{ $s->id===(int)$id ? 'active' : '' }}">{{ $s->name }}</a>
                @endforeach
            </div>

            <div>
                <table>
                    <thead>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Market Price</th>
                        <th>Barcode</th>
                        <th>Donor</th>
                    </thead>
                    @foreach($status->find($id)->inventories as $inventory)
                        <tr>
                            <td>{{$inventory->item->name}}</td>
                            <td>{{$inventory->quantity}}</td>
                            <td>{{$inventory->itemPrice->market_price}}</td>
                            <td>{{$inventory->item->itemCodes->last()->code}}</td>
                            <td>{{$inventory->donor->name}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>    

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

