@extends('layouts.cms')

@section('title', 'Orders')
@section('css')
<link rel="stylesheet" type="text/css" href="/fe/css/main.css" />
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('contrib/sweetalert2/sweetalert2.min.css')}}"/>
@append
@section('content')
@csrf
<div class="order-container">
    <div class="row">
        <div class="col-2">
            <div class="header">
                <h1>
                    Order # {{$order->reference_number}}
                </h1>
            </div>
            <div class="body">
                <div class="list">
                    <div class="label">Order Date:</div>
                    <div class="value">{{date('F j, Y', strtotime($order->created_at))}}</div>
                </div>
                <div class="list order-status">
                    <div class="label">Order Status:</div>
                    <div class="value">
                        <div class="value-cont">
                            <span class="badge badge-success status active" id="status-text">{{$order->status}}</span>
                            <select class="form-control" id="ord_status">
                                @foreach($statuses as $status)
                                  <option value="{{$status}}">{{$status}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="button-cont">
                            <button type="button" class="button edit-btn active">
                                <img src="/fe/images/create-outline.svg" alt="">
                            </button>
                            <button type="button" class="button save-btn">
                                <img src="/fe/images/save-outline.svg" alt="">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="list payment-status">
                    <div class="label">Payment Status:</div>
                    <div class="value">
                        <div class="value-cont">
                            <span class="status active" id="payment-status-text">{{$order->payment_status}}</span>
                            <select class="form-control" id="payment_status">
                                @foreach($payment_statuses as $status)
                                <option value="{{$status}}">{{$status}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="button-cont">
                            <button type="button" class="button edit-btn active">
                                <img src="/fe/images/create-outline.svg" alt="">
                            </button>
                            <button type="button" class="button save-btn">
                                <img src="/fe/images/save-outline.svg" alt="">
                            </button>
                        </div>

                    </div>
                </div>
                <div class="list">
                    <div class="label">Purchased From:</div>
                    <div class="value">Chatbot FB Messenger</div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="header">
                <h1>
                    Customer Information
                </h1>
            </div>
            <div class="body">
                <div class="list">
                    <div class="label">Customer Name:</div>
                    <div class="value">{{$order->firstname . ' ' . $order->lastname}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <div class="header">
                <h1>
                    Shipping Address
                </h1>
            </div>
            <div class="body">
                <p>{{$order->shipping_address}}</p>
            </div>
        </div>
        <div class="col-2">
            <div class="header">
                <h1>
                    Billing Address
                </h1>
            </div>
            <div class="body">
                <p>{{$order->shipping_address}}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <div class="header">
                <h1>
                    Payment Method
                </h1>
            </div>
            <div class="body">
                <p>{{$order->payment_method}}</p>
            </div>
        </div>
        <div class="col-2">
            <div class="header">
                <h1>
                    Shipping & Handling Information
                </h1>
            </div>
            <div class="body">
                <p>N/A</p>
            </div>
        </div>
    </div>


    <div class="header">
        <h1>
            Ordered Items
        </h1>
    </div>

    <div class="col">
        <div class="body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                  </thead>
                  <?php $orderItems = $order['order_items']; $grandTotal = 0;?>
                  @foreach($orderItems as $item)
                  <tr>
                      <td>{{$item->catalog_name}}</td>
                      <td>{{$item->sku}}</td>
                      <td>&#8369;{{number_format($item->price, 2)}}</td>
                      <td>{{$item->quantity}}</td>
                      <td>&#8369;{{number_format(($item->price * $item->quantity), 2)}}</td>
                  </tr>
                  <?php $grandTotal += ($item->price * $item->quantity);?>
                  @endforeach
                <tfoot>
                    <tr>
                        <td colspan="4" class="bg-light-2 text-right">Subtotal</td>
                        <td class="bg-light-2 ">&#8369; {{number_format($grandTotal, 2)}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<!-- SweetAlert2 -->
<script src="{{url('contrib/sweetalert2/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
    (function ($) {
        function togglePaymentEdit() {
            $('.payment-status .status').toggleClass('active');
            $('.payment-status .form-control').toggleClass('active');
            $('.payment-status .button').toggleClass('active');
        }

        function toggleOrderEdit() {
            $('.order-status .status').toggleClass('active');
            $('.order-status .form-control').toggleClass('active');
            $('.order-status .button').toggleClass('active');
        }

        $('.payment-status .edit-btn').on('click', function () {
            togglePaymentEdit();
        });

        $('.payment-status .save-btn').on('click', function () {
            togglePaymentEdit();
            

            $.ajax({
              url: "{{route('admin.order.update.payment.status')}}",
              method: "POST",
              data: {
                _id: "{{$order->_id->__toString()}}",
                _token: $('input[name=_token]').val(),
                payment_status: $('#payment_status').val(),
              },
              success: function(response){
                $('#payment-status-text').text($('#payment_status').val());
                toggleOrderEdit();
              },
              error: function(jqXHR, jqStatus, jqThrown){
                var response = JSON.parse(jqXHR.responseText);
                Swal.fire({
                    icon: 'error',
                    title: response,
                }).then(function(){
                    window.location.href = window.location.href;
                });
              }
            });
        });

        $('.order-status .edit-btn').on('click', function () {
            toggleOrderEdit();
        });

        $('.order-status .save-btn').on('click', function () {

            $.ajax({
              url: "{{route('admin.order.update.status')}}",
              method: "POST",
              data: {
                _id: "{{$order->_id->__toString()}}",
                _token: $('input[name=_token]').val(),
                status: $('#ord_status').val(),
              },
              success: function(response){
                $('#status-text').text($('#ord_status').val());
                toggleOrderEdit();
              },
              error: function(jqXHR, jqStatus, jqThrown){
                var response = JSON.parse(jqXHR.responseText);
                Swal.fire({
                    icon: 'error',
                    title: response,
                }).then(function(){
                    window.location.href = window.location.href;
                });
              }
            });
            
        });
    })(jQuery);

</script>
@append
