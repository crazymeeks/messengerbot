<button type="button" class="btn btn-primary add-channel-btn" data-toggle="modal" data-target="#channelModal">+ Add Channel </button>

<div class="modal fade" id="channelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h1 class="title">Connect your channel</h1>
				<p>Select the Channel that you want to connect.</p>
				<div class="channel-container">
					<div class="channel">
						<a href="javascript:void(0);" data-type="messenger" id="messenger">
                            <img src="{{url('app_assets/adminhtml/icons/messenger-logo.png')}}" height="100px;" alt="">
                        </a>
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					<div class="channel">
						<img src="https://via.placeholder.com/100" alt="">
						<p class="title">Messenger</p>
					</div>
					
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="btn-next" class="btn btn-primary setup-btn" disabled>Next</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="setupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h1 class="title">Connecting Facebook Messenger</h1>
        <p>This integration allows your team to chat with your customers over Facebook Messenger.</p>
        <p class="text-warning">A Facebook page is required.</p>

        <!-- <fb:login-button scope="public_profile,email,pages_show_list,read_insights,pages_messaging" data-button-type="continue_with" data-size="large" onlogin="checkLoginState();">
        </fb:login-button> -->
		<a href="javascript:void(0);" class="btn btn-primary" id="fbLogin">Continue with Facebook</a>
		<br>
		<br>
        <div id="content">
            <div class="form-group" id="pages-selection">
			</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary back-modal-btn">Back</button>
        <button type="button" class="btn btn-primary" id="btn-done" disabled data-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>