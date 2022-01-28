@extends('layouts.cms')

@section('title', 'Facebook flow')

@section('css')
<link rel="stylesheet" href="{{url('/contrib/codebox/css/codemirror.css')}}">
<link rel="stylesheet" href="{{url('/contrib/codebox/css/ayu-dark.css')}}">
<link rel="stylesheet" href="{{url('/contrib/sweetalert2/sweetalert2.min.css')}}">
@append

@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST">
            @csrf
            @if($facebook->_id)
                <input type="hidden" id="_id" value="{{$facebook->_id->__toString()}}"/>
            @endif
            <div class="form-group">
                <textarea id="code" name="flow">{{$facebook->flow}}</textarea>
            </div>
            <div class="form-group">
                <input type="submit" id="btn-save" name="save" value="Save" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{url('/contrib/codebox/js/codemirror.js')}}"></script>
<script src="{{url('/contrib/codebox/js/xml.js')}}"></script>
<script src="{{url('/contrib/sweetalert2/sweetalert2.min.js')}}"></script>

<script type="text/javascript">
$(function(){

    var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('code'), {
        mode: 'xml',
        lineNumbers: true,
        theme: 'ayu-dark',
    });

    $('#btn-save').on('click', function(evt){
        evt.preventDefault();
        var form_data = {
            flow: myCodeMirror.getValue(),
            _token: $('input[name=_token]').val(),
        };

        if ($('#_id').val()) {
            form_data.id = $('#_id').val();
        }

        $.ajax({
            url: "{{route('admin.facebook.flow.post.create')}}",
            data: form_data,
            method: "POST",
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: 'Flow successfully saved',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(jqXHR, jqStatus, jqThrown){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops. Something went wrong! Please try again',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });
});
</script>
@append