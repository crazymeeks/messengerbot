@extends('layouts.cms')

@section('title', 'Catalog list')
@section('css')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('contrib/sweetalert2/sweetalert2.min.css')}}"/>
<!-- Datatable -->
<link rel="stylesheet" href="{{url('/contrib/admin-lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @csrf
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3></h3>
                <a href="{{route('admin.catalog.get.create')}}" class="btn btn-primary">Create new Catalog</a>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="product-listing" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Discount Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    
                    </tbody>
                    <tfoot>
                    <tr>
                    
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Discount Price</th>
                        <th>Status</th>
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
@endsection
@section('script')
<!-- DataTables -->
<script src="{{url('/contrib/admin-lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/contrib/admin-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{url('contrib/sweetalert2/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
$(function () {
    $('#product-listing').DataTable({
        order: [[0, 'desc']],
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{route('admin.catalog.datatable')}}",
        },
        columns: [
            {data: 'name'},
            {data: 'sku'},
            {data: 'price'},
            {data: 'discount_price'},
            {data: 'status'},
            {data: '_id'}
        ],
        columnDefs: [
            
            {
                targets: [5],
                searchable: false,
                orderable: false,
                render: function(data, type, row, meta){
                    var toggle_active_class = row.status == 'Active' ? 'fa-eye-slash' : 'fa-eye';
                    var action = '<a href="javascript:void(0);" data-name="' + row.name + '" data-text="' + row.status + '" class="ax-toggle-active" data-id="' + row._id + '"><i class="fa ' + toggle_active_class + '"></i></a>';
                    action += '&nbsp;&nbsp;<a href="/backend/catalog/edit/' + row._id +'"><i class="fa fa-edit"></i></a>';
                    action += '&nbsp;&nbsp;<a href="javascript:void(0);" class="ax-delete" data-id="' + row._id + '"><i class="fa fa-times-circle"></i></a>';
                    return action;
                }
            }
        ],
        initComplete: function(){
            $('.ax-delete').on('click', function(){
                var form_data = {
                    _token: $('input[name=_token]').val(),
                    _id: $(this).attr('data-id')
                };
                Swal.fire({
                    title: 'Are you sure you?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `Cancel`,
                }).then(function(result){
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{route('admin.catalog.post.delete')}}",
                            method: "POST",
                            data: form_data,
                            success: function(response){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Catalog successfully deleted!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(){
                                    window.location.href = window.location.href;
                                });
                            },
                            error: function(jqXHR, jqStatus, jqThrown){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops! Something went wrong while deleting catalog. Please try again!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });

            $('.ax-toggle-active').on('click', function(){
                var form_data = {
                    _token: $('input[name=_token]').val(),
                    _id: $(this).attr('data-id')
                };
                var text = $(this).attr('data-text') == 'Active' ? 'deactivate' : 'activate';
                Swal.fire({
                    title: 'Are you sure you want to ' + text + ' ' + $(this).attr('data-name') + '?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                }).then(function(result){
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{route('admin.catalog.post.toggle.activate')}}",
                            method: "POST",
                            data: form_data,
                            success: function(response){
                                Swal.fire({
                                    icon: 'success',
                                    title: response,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(){
                                    window.location.href = window.location.href;
                                });
                            },
                            error: function(jqXHR, jqStatus, jqThrown){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops! Something went wrong while activate/deactivating catalog. Please try again!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });
        }
    });
});
</script>
@stop