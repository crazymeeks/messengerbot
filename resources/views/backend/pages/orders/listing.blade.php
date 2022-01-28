@extends('layouts.cms')

@section('title', 'Orders')

@section('css')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('contrib/sweetalert2/sweetalert2.min.css')}}"/>
<!-- Datatable -->
<link rel="stylesheet" href="{{url('/contrib/admin-lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3></h3>
                @csrf
                <!-- /.box-header -->
                <div class="box-body">
                    <table border="0" cellspacing="5" cellpadding="5">
                        <tbody>
                            <tr>
                                <td><label for="" class="control-label"><i class="fa fa-filter"></i> Filter:</label></td>
                                <td>
                                    <select name="filterState" class="form-control" id="filterByState">
                                        <option value="">All</option>
                                        <option value="Pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="In Transit">In Transit</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Complete">Complete</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table id="order-listing" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reference #</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Order Date(Y-m-d)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Reference #</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Order Date(Y-m-d)</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    </table>
                </div>
            <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <!-- The time line -->
        <ul class="timeline">
            <!-- timeline time label -->
            <li class="time-label">
                  <span class="bg-red">
                    Recent activities
                  </span>
            </li>
            <!-- END timeline item -->
            @if(count($recent_activities) > 0)
                @foreach($recent_activities as $activity)
                <li>
                    <i class="fa fa-user bg-aqua"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{time_elapsed_string($activity->updated_at)}}</span>

                        <h3 class="timeline-header no-border">{{$activity->value}}</h3>
                    </div>
                </li>
                @endforeach
            @else
                <li>
                    <i class="fa fa-user bg-aqua"></i>
                    <div class="timeline-item">
                        <h3 class="timeline-header no-border">No recent activity</h3>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</div>
@include('backend.pages.orders.modal')
@endsection

@section('script')
<!-- DataTables -->
<script src="{{url('/contrib/admin-lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/contrib/admin-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{url('contrib/sweetalert2/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
$(function () {
    
        initModalAction();

        function initModalAction(){   

            $('#modal-btn__close, #modal-mini__close').on('click', function(){
                var previous_state = $('body').data('modal_order_curr_state');
                $('#modal-order__state').val(previous_state);
            });


            $('#modal-btn__save').on('click', function(){

                var form_data = {
                    reference_number: $('body').data('modal_order_ref_no'),
                    state: $('#modal-order__state').val(),
                    _token: $('input[name=_token]').val()
                };

                // 

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Updating state of Order.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "",
                            method: "POST",
                            data: form_data,
                            success: function(response){
                                Swal.fire(
                                'Saved',
                                'Order state has been updated.',
                                'success'
                                ).then(function(){
                                    window.location.href = window.location.href;
                                });

                            },
                            error: function(jqXHR, jqStatus, jqThrown){

                            }
                        });

                    }
                });
                
            });
        }



        var dt = $('#order-listing').DataTable({
            // order: [[0, 'desc']],
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{route('admin.order.datatable')}}",
                data: function(data){
                    var filterByState = $('#filterByState').val();
                    data.filter = {
                        state: filterByState
                    };
                }
            },
            columns: [
                {data: 'reference_number'},
                {data: 'firstname'},
                {data: 'lastname'},
                {data: 'email'},
                {data: 'mobile_number'},
                {data: 'payment_method'},
                {data: 'status'},
                {data: 'payment_status'},
                {data: 'created_at'},
                {data: '_id'}
            ],
            columnDefs: [
                
                {
                    targets: [9],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        return '<a href="/backend/order/edit/' + row._id + '"><i class="fa fa-edit "></i></a>';
                    }
                }
            ],
            initComplete: function(settings, json){
                $('#order-listing').on('click', '.action-update_state', function(){
                    var reference_number = $(this).attr('data-ref-no');
                    var order_id = $(this).attr('data-order-id');
                    var fullname = $(this).attr('data-fullname');
                    var shipping = $(this).attr('data-shipping');
                    var curr_state = $(this).attr('data-current-state');

                    $('body').data('modal_order_ref_no', reference_number);
                    $('body').data('modal_order_curr_state', curr_state);

                    $('#modal-reference__number').text(reference_number);
                    var ordered_list = '<ul>';
                    ordered_list += '<li>' + fullname + '</li>';
                    ordered_list += '<li>Shipping address: ' + shipping + '</li>';
                    ordered_list += '</ul>';
                    $('#modal-order__info').html(ordered_list);
                    $('#modal-order__state').val(curr_state);
                });
            }
        });

        $('#filterByState').on('change', function(){
            dt.draw();
        });
    
});
</script>
@stop
