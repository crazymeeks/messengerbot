<?php

namespace Tests\Feature\Backend\Conversation;

class LiveChatControllerTest extends \Tests\TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        $admin_user = new \stdClass();
        $admin_user->_id = 1;
        $this->mockChatReply();
        
        session()->put(\App\Http\Controllers\Backend\Authentication\LoginController::BACKEND_LOGGED_IN_SESSION_NAME, $admin_user);
    }

    public function testAdminReplyToConversation()
    {

        $chatter = new \App\Models\Chatter();
        $chatter->insertOne([
            'fb_id' => '3429492650408259',
            'fullname' => 'John Doe',
            'read' => '0'
        ]);
        
        
        $response = $this->json('POST', route('admin.messenger.chat.reply'), [
            'page_id' => '106274717810000',
            'recipient_id' => '3429492650408259',
            'message' => 'Admin reply'
        ]);
        $result = $chatter->findOne(['fb_id' => '3429492650408259']);
        
        $response->assertStatus(200);
        $this->assertEquals('John Doe', $result->fullname);
    }

    /**
     * Admin ended conversation
     */
    public function testAdminEndedLiveConversation()
    {

        $chatter = new \App\Models\Chatter();
        $chatter->insertOne([
            'fb_id' => '3429492650408259',
            'fullname' => 'John Doe',
            'read' => '0'
        ]);
        
        
        $response = $this->json('POST', route('admin.messenger.end.live.chat'), [
            'page_id' => '106274717810000',
            'recipient_id' => '3429492650408259',
            'message' => 'End Conversation'
        ]);

        $result = $chatter->findOne(['fb_id' => '3429492650408259']);
        $reply = new \App\Models\ConversationReply();
        $reply = $reply->findOne(['chatter_id' => $result->_id]);
        $response->assertStatus(200);
        $this->assertEquals('CSR ended live chat', $reply->reply);
    }

    public function testCheckCustomerWhoWantsLiveChat()
    {
        $chatter = new \App\Models\Chatter();
        $chatter->insertOne([
            'fb_id' => '3429492650408259',
            'fullname' => 'John Doe',
            'read' => '0'
        ]);
        $next_flow = new \App\Models\NextFlow();
        $next_flow->insertOne([
            'bot' => \App\Models\NextFlow::BOT_MESSENGER,
            'bot_user_id' => '3429492650408259',
            'next' => \FbMessengerBot\HttpClient\Server::ON_LIVE_SUPPORT_MODE
        ]);
        
        $response = $this->json('POST', route('admin.messenger.customer.need.live.chat'), [
            'recipient_id' => '3429492650408259'
        ]);
        
        $response->assertStatus(200);
    }

    private function mockChatReply()
    {
        $curl = \Mockery::mock(\Ixudra\Curl\CurlService::class);
        $curl->shouldReceive('to')
             ->with(\Mockery::any())
             ->andReturnSelf();
        $curl->shouldReceive('withData')
             ->with(\Mockery::any())
             ->andReturnSelf();
        $curl->shouldReceive('withResponseHeaders')
             ->andReturnSelf();
        $curl->shouldReceive('returnResponseObject')
             ->andReturnSelf();
        $curl->shouldReceive('post')
             ->andReturn(json_decode(json_encode([
                 'status' => 200
             ])));
        $this->app->bind(\Ixudra\Curl\CurlService::class, function() use($curl){
            return $curl;
        });
    }
}