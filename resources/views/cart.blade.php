@extends('layouts.app')

@section('content')

    <div class="container">
        <p><a href="{{ url('shop') }}">Home</a> / {!! trans('cart.general.cart') !!}</p>
        <h1>{!! trans('cart.general.your_cart') !!}</h1>

        <hr>

        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if (session()->has('error_message'))
            <div class="alert alert-danger">
                {{ session()->get('error_message') }}
            </div>
        @endif

        @if (sizeof(Cart::instance('main')->content()) > 0)

            <table class="table">
                <thead>
                <tr>
                    <th class="table-image">{!! trans('cart.general.image') !!}</th>
                    <th>{!! trans('cart.general.item') !!}</th>
                    <th>{!! trans('cart.general.quantity') !!}</th>
                    <th>{!! trans('cart.general.price') !!}</th>
                    <th class="column-spacer"></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach (Cart::instance('main')->content() as $item)
                    <tr>
                        <td class="table-image"><a href=""><img src="" alt="product" class="img-responsive cart-image"></a></td>
                        <td><a href="">{{ $item->name }}</a></td>
                        <td>
                            <select class="quantity" data-id="{{ $item->rowId }}">
                                <option {{ $item->qty == 1 ? 'selected' : '' }}>1</option>
                                <option {{ $item->qty == 2 ? 'selected' : '' }}>2</option>
                                <option {{ $item->qty == 3 ? 'selected' : '' }}>3</option>
                                <option {{ $item->qty == 4 ? 'selected' : '' }}>4</option>
                                <option {{ $item->qty == 5 ? 'selected' : '' }}>5</option>
                            </select>
                        </td>
                        <td>{{ $item->subtotal()->format() }}</td>
                        <td class=""></td>
                        <td>
                            <form action="{{ url('cart', [$item->rowId]) }}" method="POST" class="side-by-side">
                                {!! csrf_field() !!}
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="submit" class="btn btn-danger btn-sm" value="{!! trans('cart.buttons.remove') !!}">
                            </form>

                            <form action="{{ url('switchToWishlist', [$item->rowId]) }}" method="POST" class="side-by-side">
                                {!! csrf_field() !!}
                                <input type="submit" class="btn btn-success btn-sm" value="{!! trans('cart.wishlist.buttons.transfer') !!}">
                            </form>
                        </td>
                    </tr>
                @endforeach

                <tr class="border-bottom">
                    <td class="table-image"></td>
                    <td style="padding: 40px;"></td>
                    <td class="small-caps table-bg" style="text-align: right">{!! trans('cart.total') !!}</td>
                    <td class="table-bg">{{ Cart::instance('main')->total()->format() }}</td>
                    <td class="column-spacer"></td>
                    <td></td>
                </tr>

                </tbody>
            </table>

            <a href="/shop" class="btn btn-primary btn-lg">{!! trans('cart.buttons.continue') !!}</a> &nbsp;
            <a href="#" class="btn btn-success btn-lg">{!! trans('cart.buttons.checkout') !!}</a>

            <div style="float:right">
                <form action="/emptyCart" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-danger btn-lg" value="{!! trans('cart.buttons.empty') !!}">
                </form>
            </div>

        @else

            <h3>You have no items in your shopping cart</h3>
            <a href="/shop" class="btn btn-primary btn-lg">{!! trans('cart.buttons.continue') !!}</a>

        @endif

        <div class="spacer"></div>

    </div> <!-- end container -->

@endsection

@section('scripts')
    <script>
        (function(){

            $('.quantity').on('change', function() {
                var id = $(this).attr('data-id');
                var method = "PATCH";
                $.ajax({
                    type: "PATCH",
                    url: '/cart/' + id,
                    data: {
                        'quantity': this.value
                    },
                    success: function(data) {
                        window.location.href = '/cart';
                    }
                });

            });

        })();

    </script>
@endsection