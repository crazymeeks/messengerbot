@extends('layouts.cms')

@section('title', 'Messages')
@section('css')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('/contrib/sweetalert2/sweetalert2.min.css')}}"/>
@endsection
@section('content')
@csrf
<section class="content container-fluid">
    <div class="msg-header">
        <div class="chat-info">
        <p class="platform">Facebook Messenger</p>
        </div>
        <div class="button-cont">
            <button type="button" id="end-convo" class="btn btn-success" disabled="disabled">End Conversation</button>
        </div>
    </div>
    <div class="chat-container">
        <div class="user-list">
        <ul class="user-messages-cont">
            <li class="user-messages -active">
            <div class="message-details">
                <h4 class="name">{{$chatter->fullname}}</h4>
                <!-- <p class="prev-message">You: Test Test</p> -->
            </div>
            </li>
        </ul>
        </div>
        <div class="mesgs" id="vueapp">
            <div class="msg_history">
                <div v-for="conversation in conversations">

                    <div v-if="conversation.admin_user_id == null">
                        <div class="incoming_msg">
                            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
                            </div>
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                <p>@{{conversation.reply}}</p>
                                <!-- <span class="time_date"></span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="outgoing_msg">
                            <div class="sent_msg">
                                <p>@{{conversation.reply}}</p>
                                <!-- <span class="time_date"> 11:01 AM | June 9</span> -->
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="type_msg">
                <div class="input_msg_write">
                <input type="text" class="write_msg" placeholder="Type a message" />
                <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o"
                    aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
        <div class="mesgs-info">
        
        </div>
    </div>
</section>
<audio id="chatAudio">
    <source src="{{url('notify/notify.ogg')}}" type="audio/ogg">
    <source src="{{url('notify/notify.mp3')}}" type="audio/mpeg">
    <source src="{{url('notify/notify.wav')}}" type="audio/wav">
</audio>
<!-- /.content -->
@endsection

@section('script')
<script src="{{url('contrib/vuejs/vue.dev.js')}}"></script>
<script src="{{url('/contrib/sweetalert2/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
$(function(){
    
    function loadMessages() {
        var convos = <?php echo $convos;?>;
        console.log(convos);
        var vueapp = new Vue({
            el: '#vueapp',
            data: {
                conversations: convos
            }
        });

        setInterval(function(){
            $.ajax({
                url: "{{route('admin.messenger.chat.get.livefeed', ['id' => $chatter->_id->__toString()])}}",
                method: "GET",
                success: function(response){
                    console.log('LiveChat success', response);
                    for(var i = 0; i < response.data.length; i++){
                        
                        if (response.data[i].reply == 'Live Chat') {
                            // soundNotification();
                        }
                        vueapp.conversations.push(response.data[i]);
                    }
                },
                error: function(jqXHR, jqStatus, jqThrown){
                    console.log('LiveChat failed');
                    console.log(jqXHR);
                }
            });

            $.ajax({
                url: "{{route('admin.messenger.customer.need.live.chat')}}",
                method: "POST",
                data: {
                    _token: $('input[name=_token]').val(),
                    recipient_id: "{{$chatter->fb_id}}",
                },
                success: function(response){
                    $('#end-convo').attr('disabled', false);
                },
                error: function(jqXHR, jqStatus, jqThrown){
                    $('#end-convo').attr('disabled', true);
                }
            });
        }, 4000);

        $('.msg_send_btn').on('click', function(){
            pushMessage();
        });

        $('.write_msg').on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                pushMessage();
            }
        });

        function pushMessage(){
            var txtElem = $('.write_msg');
            var message = txtElem.val();
            
            vueapp.conversations.push({
                admin_user_id: "{{session()->get('backend_user')->_id->__toString()}}",
                reply: message,
            });
            txtElem.val('');
            txtElem.focus();
            
            $.ajax({
                url: "{{route('admin.messenger.chat.reply')}}",
                method: "POST",
                data: {
                    page_id: "{{$chatter->page_id}}",
                    recipient_id: "{{$chatter->fb_id}}",
                    message: message,
                    _token: $('input[name=_token]').val(),
                },
                success: function(response){
                    console.log(response);
                    
                },
                error: function(jqXHR, jqStatus, jqThrown){

                }
            });
        }
    }

    function soundNotification(){
        $('#chatAudio')[0].play();
    }

    function endConvo(){
        $('#end-convo').on('click', function(evt){
            evt.preventDefault();
            var self = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "End live chat with customer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then(function(result){
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('admin.messenger.end.live.chat')}}",
                        method: "POST",
                        data: {
                            _token: $('input[name=_token]').val(),
                            page_id: "{{$chatter->page_id}}",
                            recipient_id: "{{$chatter->fb_id}}",
                            message: 'Conversation ended',
                        },
                        success: function(response){
                            vueapp.conversations.push({
                                admin_user_id: "{{session()->get('backend_user')->_id->__toString()}}",
                                reply: message,
                            });
                            self.attr('disabled', true);
                        },
                        error: function(jqXHR, jqStatus, jqThrown){

                        }
                    });
                }
                
            });
        });
    }
    
    loadMessages();
    endConvo();

});

</script>
@append