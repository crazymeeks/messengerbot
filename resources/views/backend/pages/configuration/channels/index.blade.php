@extends('layouts.cms')

@section('title', 'Configuration')

@section('css')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('contrib/sweetalert2/sweetalert2.min.css')}}"/>
@endsection

@section('content')

<script type="text/javascript">

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '3286808198025708',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v7.0'           // Use this Graph API version for this call.
    });
  };

(function(d, s, id) {                      // Load the SDK asynchronously
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

</script>
@include('backend.pages.configuration.channels.modal-listing')

<p></p>
<div class="row">
    <div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Activated Channels</h3>
        </div>
        <div class="box-body">
            <div class="channels-cont">
                @if(count($channels) > 0)
                @foreach($channels as $channel)
                <div class="channel-list-items">
                    <img src="{{url('app_assets/adminhtml/icons/messenger-logo.png')}}" height="70px;" alt="">
                    <p><small>{{ucfirst($channel->type_identification)}}</small></p>
                </div>
                @endforeach
              @else
                <p>No connected channels</p>
              @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

<!-- SweetAlert2 -->
<script src="{{url('contrib/sweetalert2/sweetalert2.min.js')}}"></script>

<script type="text/javascript">

$(function(){


    var channel_type = 'channel_type';
    var pageInfo = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Start: modal
    $('.setup-btn').on('click', function(){
	
        $('#channelModal').modal('toggle');
            
        $('#setupModal').modal({
            show: true
        });
        
    });
    
    $('.back-modal-btn').on('click', function(){
        
        $('#setupModal').modal('toggle')
            
        $('#channelModal').modal({
            show: true
        });
        
    });

    // End: modal

    $('#messenger').on('click', function(){
        $('body').data(channel_type, 'messenger');
        $('#btn-next').prop('disabled', false);
    });


    // FB Login
    $('#fbLogin').on('click', function(){
      var fbButton = $(this);
      pageInfo = [];
      FB.login(function(response){
          if (response.status == 'connected') {
              var userId = response.authResponse.userID;

              $.ajax({
                  url: 'https://graph.facebook.com/' + userId + '/accounts' ,
                  method: 'GET',
                  data: {
                    access_token: response.authResponse.accessToken
                  },
                  success: function(response){
                    
                      var select = "<label class='control-label'>Select Page: </label><select class='form-control' id='select-pages'>";
                      
                      var page_data = {};
                      for(var i = 0; i < response.data.length; i++){
                          
                          select += "<option value='" + response.data[i].id + "' data-at='" + response.data[i].access_token + "' data-id='" + response.data[i].id + "' data-name='" + response.data[i].name + "'>" + response.data[i].name + "</option>";

                          page_data = {
                              access_token: response.data[i].access_token,
                              id: response.data[i].id,
                              name: response.data[i].name
                          };

                          pageInfo.push(page_data);
                      }

                      select += '</select>';

                      if (pageInfo.length <= 0) {
                        select = 'No connected page(s) or page have been unlinked.';
                      }

                      fbButton.hide();
                      $('#content').find('#pages-selection').append(select);
                      $('#btn-done').prop('disabled', false);
                      
                  }
              });

          } else {
              console.log('Unable to login');
          }
      }, {
          scope: 'public_profile,email,pages_show_list,read_insights,pages_messaging'
      });
    });

    $('#btn-done').on('click', function(){

        var $select = $('#select-pages option:selected');
        var page_id = $select.attr('data-id');
        var access_token = $select.attr('data-at');
        var page_name = $select.attr('data-name');

        $.ajax({
          url: "{{route('admin.config.channels.add')}}",
          method: "POST",
          data: {
            access_token: access_token,
            id: page_id,
            name: page_name,
            type: $('body').data(channel_type),
          },
          success: function(response){

            $('body').data(channel_type, '');

            Swal.fire({
                icon: 'success',
                title: response.success,
            }).then(function(){
                window.location.href = window.location.href;
            });
          },
          error: function(jqXHR, jqStatus, jqThrown){
            var response = JSON.parse(jqXHR.responseText);
            Swal.fire({
                icon: 'error',
                title: response.error,
            }).then(function(){
                window.location.href = window.location.href;
            });
          }
        });
    });


    <?php
    if (session()->has('success')):    
    ?>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: "{{session()->get('success')}}",
            showConfirmButton: false,
            timer: 1500
        });
    <?php endif;?>
});
</script>
@endsection