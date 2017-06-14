<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                /*height: 100vh;*/
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 5px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .links > a.active {
                color: rgba(255, 0, 0, .7);
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            table {
                width: 100%;
                margin-top: 20px;

            }
            table th, table td {
                border: 1px solid #eee;
                color: #000 !important;
            }
        </style>
    </head>
    <body>
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
                                <td>{{$inventory->quantities->first()->number}}</td>
                                <td>{{$inventory->itemPrice->market_price}}</td>
                                <td>{{$inventory->item->itemCodes->last()->code}}</td>
                                <td>{{$inventory->donor->name}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>    

            </div>
        </div>
    </body>
</html>
