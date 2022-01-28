@extends('layouts.cms')

@section('title', $page_title)
@section('css')
<!-- jQuery file upload -->
<link rel="stylesheet" href="{{url('contrib/jquery-upload-file/css/uploadfile.css')}}"/>
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('contrib/sweetalert2/sweetalert2.min.css')}}"/>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" enctype="multipart/form-data" id="managepage-form" method="POST" autocomplete="off">
                @csrf
                
                <div class="box-body">

                    <div class="box-header">
                        <h3 class="box-title">Post <span class="text-danger">*</span>
                            <small>Enter your post content here</small>
                        </h3>
                    </div>
                    <div class="box-body pad">
                        <textarea id="post" name="post" rows="10" cols="80">{{old('post')}}</textarea>
                        @if($errors->first('description'))
                        <p class="text-danger error">{{$errors->first('post')}}</p>
                        @endif
                    </div>
                
                </div>
                <!-- /.box-body -->
        
                <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{url('contrib/jquery-upload-file/dist/jquery-file-upload.min.js')}}"></script>
<!-- ckeditor -->
<script src="{{url('contrib/admin-lte/bower_components/ckeditor/ckeditor.js')}}"></script>
<!-- jquery validate -->
<script src="{{url('contrib/jquery-validation-1.19.2/dist/jquery.validate.min.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{url('contrib/sweetalert2/sweetalert2.min.js')}}"></script>
<script>
    $(function(){

        CKEDITOR.replace('post', {
            removeButtons: 'Cut,Copy,Paste,Undo,Redo,Anchor,Styles,Format,Source,Superscript,Subscript,RemoveFormat'
        });
        
        
        // jQuery validator
        $('#managepage-form').validate({
            ignore: [],
            rules: {
                post: {
                    required: function(textarea){
                        CKEDITOR.instances[textarea.id].updateElement();
                        var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                        return editorcontent.length === 0;
                    }
                }
            },
            errorClass: 'text-danger',
            submitHandler: function(form){

                Swal.fire({
                    icon: 'success',
                    title: 'Post have been post to your page successfully',
                }).then(function(){
                    window.location.href = window.location.href;
                });
                
                
            }
        });

        
        
    });
</script>
@endsection