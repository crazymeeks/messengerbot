@extends('layouts.cms')
@section('title', 'Create|Update Catalog')
@section('css')
<!-- jQuery file upload -->
<link rel="stylesheet" href="{{url('contrib/jquery-upload-file/css/uploadfile.css')}}"/>
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('contrib/sweetalert2/sweetalert2.min.css')}}"/>
@endsection
@section('content')
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"></h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" id="catalog-form" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="box-body">
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{$catalog->name}}" placeholder="Enter catalog name...">
                </div>
                <div class="form-group">
                    <label for="sku">SKU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sku" name="sku" value="{{$catalog->sku}}" placeholder="Enter catalog sku...">
                </div>
                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" cols="30" rows="10">{{$catalog->description}}</textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price <span class="text-danger">*</span></label>
                    <input type="number" id="price" class="form-control" name="price" value="{{$catalog->price}}" placeholder="Enter catalog price...">
                    <p class="help-block">Number only.</p>
                </div>
                <div class="form-group">
                    <label for="discount_price">Discount Price</label>
                    <input type="number" class="form-control" id="discount_price" value="{{$catalog->discount_price}}" name="discount_price" placeholder="Enter catalog discount price...">
                </div>
                <div class="form-group">
                    <label for="catalog_images">Images <span class="text-danger">*</span></label>
                    <div id="fileuploader">Upload</div>
                    <input type="hidden" name="has_image" id="has_image" value="{{$image_count}}">
                    @if($catalog->_id)
                    <?php
                    $images = explode(';', $catalog->image_urls);
                    foreach($images as $image_url):
                    ?>
                    <div class="uploaded-images-container">
                        <div class="uploaded-image">
                            <img src="{{url($image_url)}}" alt="{{$catalog->name}}" height="100px;">
                            <input type="hidden" name="catalog_images[]" class="catalog-images" value="{{$image_url}}">
                        </div>
                        <div class="uploaded-image-btn-red">Delete</div>
                    </div>
                    <?php endforeach;?>
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
@endsection

@section('script')
<script src="{{url('contrib/jquery-upload-file/dist/jquery-file-upload.min.js')}}"></script>
<!-- ckeditor -->
<script src="{{url('contrib/admin-lte/bower_components/ckeditor/ckeditor.js')}}"></script>
<!-- jquery validate -->
<script src="{{url('contrib/jquery-validation-1.19.2/dist/jquery.validate.min.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{url('contrib/sweetalert2/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
(function($){

    CKEDITOR.replace('description', {
        removeButtons: 'Cut,Copy,Paste,Undo,Redo,Anchor,Styles,Format,Source,Superscript,Subscript,RemoveFormat'
    });

    var image_count = "<?php echo $image_count;?>";
        // https://www.jqueryscript.net/other/jQuery-Plugin-For-Multiple-File-Uploader-Upload-File.html
        var fileUploader = $("#fileuploader").uploadFile({
            url:"{{route('admin.catalog.image.upload')}}",
            method: "POST",
            enctype: "multipart/form-data",
            fileName:"catalog_image",
            allowedTypes: "jpg,jpeg,png",
            formData: {  
                _token: $('input[name=_token]').val()
            },
            deleteStr: "Delete",
            showError: true,
            showDelete: true,
            autoSubmit: true,
            multiple: true,
            nestedForms: true,
            statusBarWidth:500,
            onSuccess: function(files, response, xhr, pd){
                image_count++;
                $('#has_image').val(image_count);
                
                var str = pd.filename[0].innerText;
            },
            deleteCallback: function(response, pd){
                
                var str = pd.filename[0].innerText;
                
                var image_index = str.charAt(0);
                
                $.ajax({
                    url: "{{route('admin.catalog.image.delete')}}",
                    method: "POST",
                    data: {
                        _token: $('input[name=_token]').val(),
                        image_index: image_index
                    },
                    success: function(response){
                        console.log(response);
                    }
                });
                image_count--;
                if ($('.uploaded-images-container').length > 0) {
                    image_count = $('.uploaded-images-container').length;
                }
                $('#has_image').val(image_count);
            },
            
        });


        // jQuery validator
        $('#catalog-form').validate({
            ignore: [],
            rules: {
                name: 'required',
                description: {
                    required: function(textarea){
                        CKEDITOR.instances[textarea.id].updateElement();
                        var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                        return editorcontent.length === 0;
                    }
                },
                price: {
                    required: true,
                    number: true
                },
                sku: 'required',
            },
            errorClass: 'text-danger',
            submitHandler: function(form){

                if ($('.ajax-file-upload-error').length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid catalog image type detected. Please upload only jpg,jpeg,png.',
                    });
                    return false;
                }
                
                if (parseInt($('#has_image').val()) <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Catalog image is required',
                    });
                    return false;
                }
                
                var form_data = {
                        _token: $('input[name=_token]').val(),
                        name: $('#name').val(),
                        sku: $('#sku').val(),
                        description: $('#description').val(),
                        price: $('#price').val(),
                        discount_price: $('#discount_price').val(),
                };


                <?php if($catalog->_id):?>
                    form_data._id = "<?php echo $catalog->_id->__toString();?>";
                    var catalog_images = [];
                    $('.catalog-images').each(function(index){
                        catalog_images.push($(this).val());
                    });
                    form_data.catalog_images = catalog_images;
                    
                <?php endif;?>
                $.ajax({
                    url: "{{route('admin.catalog.post.create')}}",
                    method: "POST",
                    data: form_data,
                    success: function(response){
                        Swal.fire({
                            icon: 'success',
                            title: 'Product successfully saved.',
                        }).then(function(){
                            window.location.href = window.location.href;
                        });
                    },
                    error: function(jqXHR, jqStatus, jqThrown){
                        console.log(jqXHR);
                    }
                });
            }
        });

        $('.uploaded-image-btn-red').on('click', function(){
            $(this).closest('.uploaded-images-container').remove();
            image_count--;
            $('#has_image').val(image_count);
        });

})(jQuery);
</script>
@append