@extends('layouts.cms')

@section('title', $page_title)
@section('css')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('/contrib/sweetalert2/sweetalert2.min.css')}}"/>
<!-- Datatable -->
<link rel="stylesheet" href="{{url('/contrib/admin-lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="conversation-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Fullname</th>
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
<script type="text/javascript">
$(function(){

    $('#conversation-table').DataTable({
        order: [[0, 'desc']],
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{route('admin.messenger.get.conversation')}}",
        },
        columns: [
            {data: 'fullname'},
            {data: 'id'}
        ],
        columnDefs: [
            
            {
                targets: [1],
                searchable: false,
                orderable: false,
                render: function(data, type, row, meta){
                    return row.edit_action; // + '&nbsp;&nbsp;' + '&nbsp;&nbsp;' + row.publish_toggle + '&nbsp;&nbsp;' + row.delete_action;
                }
            }
        ]

    });

});
</script>
@stop