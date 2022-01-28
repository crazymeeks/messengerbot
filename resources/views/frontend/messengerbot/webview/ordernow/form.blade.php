@extends('frontend.messengerbot.webview.ordernow.parent')
@section('content')
    <style>
        .text-danger {
            color: red;
        }
    </style>
    <div class="loader-cont">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="order-page">
        <div class="header">
            <h4 class="title">Choose what products you want to order below</h4>
        </div>
        <div class="alert-box">
            <h5></h5>
        </div>
        <div class="container">
            <div class="product-list">
                
                <?php
                $grandTotal = 0;
                ?>
                @foreach($products as $product)
                <?php
                $total = $product->price * $product->quantity;
                $grandTotal += $total;
                ?>
                <input type="hidden" name="mview_sender_id" id="mview_sender_id" value="{{$senderid}}">
                <div class="product">
                    <img src="{{$product->image_url}}" alt="{{$product->name}}" class="product-img">
                    <div class="product-desc">
                        <h1 class="title">
                            {{$product->name}}
                        </h1>
                        <p class="price">&#8369; {{$product->price}}
                        </p>
                    </div>
                    <div class="counter-cont qty-cont">
                        <div class="add-btn" data-id="{{$product->id}}" data-price="{{$product->price}}">
                            <i class="fi-xwsuxl-plus-solid"></i>
                        </div>
                        <h1 class="number quantity">{{$product->quantity}}</h1>
                        <div class="minus-btn" data-id="{{$product->id}}" data-price="{{$product->price}}">
                            <i class="fi-xwsuxl-minus-solid"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="footer">
            <p class="total">Total:</p>
            <p class="total-number">&#8369; <span id="total">{{$grandTotal}}</span></p>
            <button class="next-btn primary-btn" id="btn-next">Next</button>
        </div>
    </div>
    <div class="order-form -hidden">
        
        <div class="header">
            <h4 class="title">Please place your full name and mobile number below!</h4>
        </div>
        <form id="messengerbot-webview-checkout" method="POST">
            @csrf
            <div class="container">
                    <div class="input-cont">
                        <label for="fname">First Name:</label>
                        <input type="text" id="firstname" name="firstname">
                    </div>
                    <div class="input-cont">
                        <label for="fname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname">
                    </div>
                    <div class="input-cont">
                        <label for="shipping_address">Shipping Address:</label>
                        <textarea name="shipping_address" id="shipping_address" cols="30" rows="10"></textarea>
                    </div>
                    <div class="input-cont">
                        <label for="email">Email:</label>
                        <input type="email" id="email" id="email" name="email">
                    </div>
                
            </div>
            <div class="form-footer">
                <button class="primary-btn">Checkout</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
<script src='{{url("/app_assets/frontend/messengerbot/js/script.js")}}'></script>
<!-- jquery validate -->
<script src="{{url('contrib/jquery-validation-1.19.2/dist/jquery.validate.min.js')}}"></script>
<!-- jquery debounce -->
<script type="text/javascript">

/**
 * Initial FB messenger SDK
 */
(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/en_US/messenger.Extensions.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'Messenger'));


$(function(){

    var timer;

    $('body').data('pageid', "<?php echo $pageid;?>");

    $('body').data('mview_sender_id', "<?php echo $senderid;?>");

    function debounce(func, mil){
        
        if(timer) clearTimeout(timer);
        timer = setTimeout(func, mil);
    }

    function setTotal(response) {
        $('#total').text(response.grand_total);
    }

    // validation
    $('#messengerbot-webview-checkout').validate({
        rules: {
                firstname: 'required',
                lastname: 'required',
                shipping_address: 'required',
                email: {
                    required: true,
                    email: true
                }
            },
            errorClass: 'text-danger',
            submitHandler: function(form){
                $('.loader-cont').addClass('show');
                
                var form_data = {
                    firstname: $('#firstname').val(),
                    lastname: $('#lastname').val(),
                    shipping_address: $('#shipping_address').val(),
                    email: $('#email').val(),
                    _token: $('input[name=_token]').val(),
                    mview_sender_id: $('body').data('mview_sender_id'),
                    pageid: $('body').data('pageid'),
                };
                
                $.ajax({
                    url: "{{route('messenger.webview.order.checkout')}}",
                    method: "POST",
                    data: form_data,
                    success: function(response){
                        closeMessenger();
                    },
                    error: function(jqXHR, jqStatus, jqThrown){
                        closeMessenger();
                    }
                });
            }
    });


    function closeMessenger() {
        // close FB messeger webview
        // window.location.href = 'https://www.messenger.com/closeWindow';
        // the Messenger Extensions JS SDK is done loading
        MessengerExtensions.requestCloseBrowser(function success() {
                // webview closed
            }, function error(err) {
                // an error occurred
        });
    }


    if (parseInt($('#total').text()) <= 0) {
        $('#btn-next').prop('disabled', true);
    } else {
        $('#btn-next').prop('disabled', false);
    }

    $('.add-btn').on('click', function(){

        var quantity = parseInt($(this).closest('.qty-cont').find('.quantity').text());
        quantity++;

        $(this).closest('.qty-cont').find('.quantity').text(quantity);

        var form_data = {
            _token: $('input[name=_token]').val(),
            product_id: parseInt($(this).attr('data-id')),
            quantity: parseInt(quantity),
            price: parseInt($(this).attr('data-price')),
            type: 'add'
        };
        $.ajax({
            url: "{{route('messenger.webview.add.to.cart')}}",
            method: "POST",
            data: form_data,
            success: function(response){
                setTotal(response);
                $('#btn-next').prop('disabled', false);
            }
        });
        
    });

    $('.minus-btn').on('click', function(){
        var quantity = parseInt($(this).closest('.qty-cont').find('.quantity').text());
        quantity--;
        if (quantity < 0) {
            return;
            quantity = 0;
        }


        $(this).closest('.qty-cont').find('.quantity').text(quantity);

        var form_data = {
            _token: $('input[name=_token]').val(),
            product_id: parseInt($(this).attr('data-id')),
            quantity: parseInt(quantity),
            price: parseInt($(this).attr('data-price')),
            type: 'minus'
        };
        $.ajax({
            url: "{{route('messenger.webview.add.to.cart')}}",
            method: "POST",
            data: form_data,
            success: function(response){
                setTotal(response);
                if (response.grand_total <= 0) {
                    $('#btn-next').prop('disabled', true);
                }
            }
        });
    });
});
</script>
@endsection