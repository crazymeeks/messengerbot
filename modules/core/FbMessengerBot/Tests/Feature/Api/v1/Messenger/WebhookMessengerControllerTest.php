<?php

namespace FbMessengerBot\Tests\Feature\Api\v1\Messenger;

use App\Models\NextFlow;
use App\Models\FacebookFlow;
use Ixudra\Curl\CurlService;
use FbMessengerBot\HttpClient\Server;
use Crazymeeks\MessengerBot\Builder\FlowBuilder;
use Crazymeeks\Foundation\PaymentGateway\Parameters;
use Crazymeeks\Foundation\PaymentGateway\DragonPay\Token;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay as CrazymeeksDragonpay;

class WebhookMessengerControllerTest extends \Tests\TestCase
{

    const RESPONSE = 'EVENT_RECEIVED';

    private $curl;
    private $dragonpay;

    public function setUp(): void
    {
        parent::setUp();

        $this->dragonpay = \Mockery::mock(CrazymeeksDragonpay::class)->makePartial();
        $this->token = new Token('the-encrypted-token');
        $this->parameters = \Mockery::mock(Parameters::class)->makePartial();
        $this->parameters->shouldReceive('query')
                         ->andReturn('mode=1&amount=200');

        $this->curl = \Mockery::mock(CurlService::class);

        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeader')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withResponseHeaders')
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn(json_decode(json_encode([
                       'status' => 200
                   ])));
        
        $this->curl->shouldReceive('get')
                   ->andReturn(json_decode(json_encode([
                       'content' => json_encode([
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                        ]),
                       'status' => 200
                   ])));

        
    }

    /**
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::messengerGetStarted
     */
    public function testReplyFlowAfterGetStarted(array $data)
    {
        
        $this->seedFlow();

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data, ['fbpritoken' => 'facebookToken']);
        
        $response->assertStatus(200);
        
        $next_flow = new NextFlow();
        $result = $next_flow->findOne();
        $this->assertArrayHasKey('message', $response->original[0]);
        $this->assertNull($result);
    }

    /**
     * Validating next action taken by the user. User should click on button
     * instead of typing in the chatbox
     * 
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::pickColor
     */
    public function testValidateUserNextAction(array $data, $chat_reply)
    {

        // Jepoy
        $this->seedFlow();
        $next_flow = new NextFlow();
        $next_flow->insertOne([
            'bot' => NextFlow::BOT_MESSENGER,
            'bot_user_id' => \FbMessengerBot\Tests\DataProvider\Data::FB_ID,
            'next' => 'pickColorValidation'
        ]);
        
        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $chat_reply);

        $response->assertStatus(200);
    }

    /**
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::createFlowFromClass
     */
    public function testCreateFlowFromClass(array $data)
    {
        
        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <getProducts>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\Testing\SampleClass</class>
                    </message>
                </bot>
            </getProducts>
        </main>"
        ]);

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        // $this->assertSame('Message reply from class instead of xml', $response->original[0]['message']['text']);
        $response->assertStatus(200);
    }

    /*
    public function testValidateXMLFlow()
    {
        
    }*/

    /**
     * When user initiated live chat, we should not return any response
     * to messenger bot unless chat is ended.
     * 
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::initiatedLiveChat
     */
    public function testStartLiveChat(array $data, array $user_chat)
    {
        
        $this->stubFacebookProfile();

        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <get_started>
                <bot>
                      <message>
                        <class>\FbMessengerBot\Flow\Testing\SampleClass</class>
                      </message>
                  </bot>
            </get_started>
              <live_support>
                <bot>
                      <message>
                          <text>Hi {{firstname}}, our customer service representative will be with you in a moment :)</text>
                      </message>
                  </bot>
            </live_support>
            <default>
                <bot>
                    <message>
                        <text>Sorry, I cannot understand your reply. Please type get started :)</text>
                    </message>
                </bot>
            </default>
        </main>"
        ]);
        
        $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $user_chat);
        $response->assertStatus(200);
    }


    /**
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::messengerGetStarted
     */
    public function testCreateFlowUsingWebview(array $data)
    {
        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <get_started>
                <bot>
                    <message>
                        <attachment>
                            <type>template</type>
                            <payload>
                                <template_type>button</template_type>
                                <text>Hi {{firstname}}! Welcome. Please choose from the options below:</text>
                                <buttons>
                                    <type>web_url</type>
                                    <url>https://rockyourraket.nuworks.ph</url>
                                    <title>Visit messenger</title>
                                </buttons>
                            </payload>
                        </attachment>
                    </message>
                    <!-- triggers -->
                    <next>getEmail</next>
                </bot>
            </get_started>
            <displayProducts>
                <bot>
                    <message>
                        <text>Do you want to continue?</text>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Yes</title>
                            <payload>
                                <action>yes_continue</action>
                            </payload>
                        </quick_replies>
                    </message>
                </bot>
            </displayProducts>
            <yes_continue>
                <bot>
                    <message>
                        <text>Hi {{Firstname}}, I need to get your address, please type it below:</text>
                    </message>
                    <next>getEmail</next>
                </bot>
            </yes_continue>
            <getEmail>
                <bot>
                    <message>
                        <text>We need your email as well :)</text>
                    </message>
                </bot>
            </getEmail>
            <getProducts>
                <class>\FbMessengerBot\Flow\Products</class>
            </getProducts>
            <default>
                <bot>
                    <message>
                        <text>Sorry, I cannot understand your reply</text>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Yes</title>
                            <payload>
                                <action>yes_continue</action>
                            </payload>
                        </quick_replies>
                    </message>
                </bot>
            </default>
        </main>"
        ]);

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        $message = $response->original[0]['message']['attachment']['payload']['text'];
        $this->assertSame('Hi John! Welcome. Please choose from the options below:', $message);
    }

    /**
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::messengerGetStarted
     */
    public function testSaveConversation(array $data)
    {
        $this->mockServerWithFacebookProfile();

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        
        $response->assertStatus(200);
        
        $chatter = new \App\Models\Chatter();
        $convo_reply = new \App\Models\ConversationReply();
        
        $this->assertEquals('Get Started', $convo_reply->findOne()->reply);
        $this->assertEquals('John Doe', $chatter->findOne()->fullname);

    }
    
    
    
    /**
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::getProducts
     */
    public function testGetCatalogs(array $data)
    {
        $this->createCatalog();
        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <show_products>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\Products</class>
                    </message>
                </bot>
            </show_products>
        </main>"
        ]);
        
        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        $this->assertTrue(count($response->original) > 0);
        
    }

    /**
     * Add to Cart
     */
    public function testBuyProduct()
    {

        $catalog = $this->createCatalog();

        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => '106533924527621',
                    'time' => 1600392242481,
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '3615532048467117'
                            ],
                            'recipient' => [
                                'id' => '106533924527621'
                            ],
                            'timestamp' => 1600392242268,
                            'postback' => [
                                'title' => 'Buy',
                                'payload' => json_encode([
                                    'action' => 'buy',
                                    '_id' => $catalog->getInsertedId()->__toString(),
                                ])
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <buy>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\Buy</class>
                    </message>
                    <next>buy_quantity</next>
                </bot>
            </buy>
            <buy_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\BuyQuantity</class>
                    </message>
                </bot>
            </buy_quantity>
        </main>"
        ]);

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        $nextFlow = new \App\Models\NextFlow();
        $cart = new \App\Models\Cart();
        $item = $cart->findOne();
        $nextFlow = $nextFlow->findOne();
        $this->assertEquals('save_input_cart_quantity', $nextFlow->next);
        $this->assertNotNull($nextFlow->custom_data);
        $this->assertEquals('How many Pants would you like to buy?', $response->original[0]['message']['text']);
        $this->assertInstanceOf(\MongoDB\BSON\ObjectId::class, $item->catalog_id);
        $this->assertEquals(0, $item->quantity);
        
    }

    
    /**
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::enterQuantityToBuy
     */
    public function testEnterBuyQuantity(array $data)
    {
        
        $catalog = $this->createCatalog();
        $next_flow = new \App\Models\NextFlow();
        $next_flow->insertOne([
            'bot' => \App\Models\NextFlow::BOT_MESSENGER,
            'bot_user_id' => '2912335075508834',
            'next' => 'save_input_cart_quantity',
            'custom_data' => $catalog->getInsertedId()->__toString(),
        ]);
        
        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <save_input_cart_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\SaveAddToCartBuyQuantity</class>
                    </message>
                </bot>
            </save_input_cart_quantity>
        </main>"
        ]);

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        $array = $response->original;
        $this->assertEquals('Quantity has been added to your cart :) Add more?', $array[0]['message']['text']);
    }

    /**
     * Display order summary when user opt to proceed to checkout
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::proceedToCheckout
     */
    public function testDisplayOrderSummaryWhenUserOptProceedToCheckout(array $data)
    {

        $catalog = $this->createCatalog();

        $cart = new \App\Models\Cart();
        $cart->insertOne([
            'user_id' => '2912335075508834',
            'catalog_id' => $catalog->getInsertedId(),
            'quantity' => '2'
        ]);

        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <buy>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\Buy</class>
                    </message>
                    <next>buy_quantity</next>
                </bot>
            </buy>
            <buy_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\BuyQuantity</class>
                    </message>
                </bot>
            </buy_quantity>
            <save_input_cart_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\SaveAddToCartBuyQuantity</class>
                    </message>
                </bot>
            </save_input_cart_quantity>
            <show_order_summary>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\ShowOrderSummary</class>
                    </message>
                    <message>
                        <class>\FbMessengerBot\Flow\AskShippingName</class>
                    </message>
                    <next>complete_shipping_details</next>
                </bot>
            </show_order_summary>
            <complete_shipping_details>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\AskShippingDetails</class>
                    </message>
                </bot>
            </complete_shipping_details>
        </main>"
        ]);

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        $array = $response->original;
        
        $nextFlow = new NextFlow();
        $result = $nextFlow->findOne();
        $this->assertTrue(count($array) == 2);
        $this->assertEquals('Please enter your name :)', $array[1]['message']['text']);
        $this->assertEquals('complete_shipping_details', $result->next);
    }

    /**
     * Display order summary when user opt to proceed to checkout
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::shippingInfo
     */
    public function testAskRemainingShippingQuestion(array $data)
    {
        $catalog = $this->createCatalog();

        $cart = new \App\Models\Cart();
        $cart->insertOne([
            'user_id' => '2912335075508834',
            'catalog_id' => $catalog->getInsertedId(),
            'quantity' => '2'
        ]);

        $next_flow = new NextFlow();
        $next_flow->insertOne([
            'bot' => NextFlow::BOT_MESSENGER,
            'bot_user_id' => \FbMessengerBot\Tests\DataProvider\Data::FB_ID,
            'next' => 'complete_shipping_details'
        ]);
            
        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <buy>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\Buy</class>
                    </message>
                    <next>buy_quantity</next>
                </bot>
            </buy>
            <buy_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\BuyQuantity</class>
                    </message>
                </bot>
            </buy_quantity>
            <save_input_cart_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\SaveAddToCartBuyQuantity</class>
                    </message>
                </bot>
            </save_input_cart_quantity>
            <show_order_summary>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\ShowOrderSummary</class>
                    </message>
                    <message>
                        <class>\FbMessengerBot\Flow\AskShippingName</class>
                    </message>
                    <next>complete_shipping_details</next>
                </bot>
            </show_order_summary>
            <complete_shipping_details>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\AskShippingDetails</class>
                    </message>
                </bot>
            </complete_shipping_details>
            <payment_dragonpay>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\PayViaDragonpay</class>
                    </message>
                </bot>
            </payment_dragonpay>
        </main>"
        ]);

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        $array = $response->original;
        
        $nextFlow = new NextFlow();
        $result = $nextFlow->findOne();
        
        $this->assertTrue(count($array) == 1);
        $this->assertEquals('Please enter your email :)', $array[0]['message']['text']);
        $this->assertEquals('complete_shipping_details', $result->next);
    }

    
    /**
     * @dataProvider FbMessengerBot\Tests\DataProvider\Data::payViaDragonpay
     */
    public function testCheckoutAndViaDragonpay(array $data)
    {
        $catalog = $this->createCatalog();

        $cart = new \App\Models\Cart();
        $cart->insertOne([
            'user_id' => '2912335075508834',
            'catalog_id' => $catalog->getInsertedId(),
            'quantity' => '2'
        ]);

        $shipping_detail = new \App\Models\ShippingDetails();
        $shipping_detail->insertOne([
            'user_id' => '2912335075508834',
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'mobile_number' => '093235325456',
            'shipping_address' => 'Shipping Address',
        ]);


        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <buy>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\Buy</class>
                    </message>
                    <next>buy_quantity</next>
                </bot>
            </buy>
            <buy_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\BuyQuantity</class>
                    </message>
                </bot>
            </buy_quantity>
            <save_input_cart_quantity>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\SaveAddToCartBuyQuantity</class>
                    </message>
                </bot>
            </save_input_cart_quantity>
            <show_order_summary>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\ShowOrderSummary</class>
                    </message>
                    <message>
                        <class>\FbMessengerBot\Flow\AskShippingName</class>
                    </message>
                    <next>complete_shipping_details</next>
                </bot>
            </show_order_summary>
            <complete_shipping_details>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\AskShippingDetails</class>
                    </message>
                </bot>
            </complete_shipping_details>
            <payment_dragonpay>
                <bot>
                    <message>
                        <class>\FbMessengerBot\Flow\PayViaDragonpay</class>
                    </message>
                </bot>
            </payment_dragonpay>
        </main>"
        ]);

        $this->app->bind(Server::class, function(){
            return new Server($this->curl, new FlowBuilder());
        });

        $this->dragonpay->shouldReceive('getToken')
                        ->with(\Mockery::any())
                        ->andSet('parameters', $this->parameters)
                        ->andReturn($this->token);

        $this->dragonpay->shouldReceive('filterPaymentChannel')
                        ->with(\Mockery::any())
                        ->andReturnSelf();

        $this->dragonpay->shouldReceive('getPaymentUrl')
                        ->andReturn('https://test.dragonpay.com');

        $this->app->bind(CrazymeeksDragonpay::class, function($app){
            return $this->dragonpay;
        });

        $response = $this->json('POST', route('api.v1.messenger.post.webhook'), $data);
        
        $array = $response->original;

        $this->assertEquals('https://test.dragonpay.com?mode=1&amount=200', $array[0]['message']['attachment']['payload']['buttons'][0]['url']);
    }

    private function createCatalog()
    {
        $data = [
            'name' => 'Pants',
            'description' => 'Uniqlo pants for men',
            'sku' => 'unq-pmen',
            'price' => 20,
            'image_urls' => '/images/catalogs/38493843983943.jpg',
            'discount_price' => 0,
            'status' => \App\Models\Catalog::ACTIVE,
        ];
        $catalog = new \App\Models\Catalog();
        
        $result = $catalog->insertOne($data);

        return $result;
    }

    private function stubFacebookProfile()
    {
        $server = \Mockery::mock(Server::class)->makePartial();
        $server->shouldReceive('getFacebookProfile')
               ->andReturnSelf();
        $server->shouldReceive('getFirstName')
               ->andReturn('John');
        $server->shouldReceive('getUserFacebookId')
               ->andReturn('3615532048467117');

        $this->app->bind(Server::class, function($app) use($server){
            return $server;
        });
    }

    private function mockServerWithFacebookProfile()
    {
        $server = \Mockery::mock(Server::class);

        $server->shouldReceive('setPageId')
               ->with(\Mockery::any())
               ->andReturnSelf();
        $server->shouldReceive('setUserFacebookId')
               ->with(\Mockery::any())
               ->andReturnSelf();
        $server->shouldReceive('setRecipientId')
               ->with(\Mockery::any())
               ->andReturnSelf();
        $server->shouldReceive('setPostBackPayload')
               ->with(\Mockery::any())
               ->andReturnSelf();
        $server->shouldReceive('createFlow')
               ->with(\Mockery::any())
               ->andReturnSelf();
        $server->shouldReceive('setRequest')
               ->with(\Mockery::any())
               ->andReturnSelf();
        $server->shouldReceive('getUserFacebookId')
               ->andReturn('2912335075508834');
        $server->shouldReceive('execute')
               ->andReturn(json_decode(json_encode([
                   'status' => 200
               ])));

        $server->shouldReceive('getFacebookProfile')
               ->andReturnSelf();
        $server->shouldReceive('getFirstName')
               ->andReturn('John');
        $server->shouldReceive('getLastName')
               ->andReturn('Doe');
        $server->shouldReceive('getPageId')
               ->andReturn('102320392030');
        $server->shouldReceive('resetSpecifics')
               ->andReturn(true);

        $this->app->bind(Server::class, function($app) use($server){
            return $server;
        });

    }

    private function seedFlow()
    {
        $facebook_flow = new FacebookFlow();
        $facebook_flow->insertOne([
            'flow' => "<main>
            <get_started>
                <bot>
                    <message>
                        <text>Pick a color</text>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Red</title>
                            <payload>
                                <action>color_red</action>
                            </payload>
                            <image_url>https://gog.com/img/test.png</image_url>
                        </quick_replies>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Red</title>
                            <payload>
                                <action>color_red</action>
                            </payload>
                            <image_url>https://gog.com/img/test.png</image_url>
                        </quick_replies>
                    </message>
                    <!-- triggers -->
                    <next>pickColorValidation</next>
                </bot>
            </get_started>
            <pickColorValidation>
                <bot>
                    <message>
                        <text>Oops! Sorry, I cannot understand your response! Please choose from options below:</text>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Red</title>
                            <payload>
                                <action>color_red</action>
                            </payload>
                            <image_url>https://gog.com/img/test.png</image_url>
                        </quick_replies>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Red</title>
                            <payload>
                                <action>color_red</action>
                            </payload>
                            <image_url>https://gog.com/img/test.png</image_url>
                        </quick_replies>
                    </message>
                </bot>
            </pickColorValidation>
            <yes_continue>
                <bot>
                    <message>
                        <text>Hi {{Firstname}}, I need to get your address, please type it below:</text>
                    </message>
                    <next>getEmail</next>
                </bot>
            </yes_continue>
            <getEmail>
                <bot>
                    <message>
                        <text>We need your email as well :)</text>
                    </message>
                </bot>
            </getEmail>
            <getProducts>
                <class>\FbMessengerBot\Flow\Products</class>
            </getProducts>
            <default>
                <bot>
                    <message>
                        <text>Sorry! but I did not recognized your response! Please type 'Get Started'</text>
                    </message>
                </bot>
            </default>
        </main>"
        ]);
    }

    public function tearDown(): void
    {
        $facebook_flow = new FacebookFlow();
        $facebook_flow->deleteMany();

        $next_flow = new NextFlow();
        $next_flow->deleteMany();

        parent::tearDown();
    }
}


class ProductList implements \Chatbot\Api\FlowInterface
{


    public function getResponse(Server $server): array
    {

        return [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => 'generic',
                    'elements' => [
                        'buttons' => [
                            [
                                'type' => 'web_url',
                                'url' => 'https://rockyourraket.nuworks.ph',
                                'title' => 'Shop Now!'
                            ],
                            [
                                'type' => 'web_url',
                                'url' => 'https://.nuworks.ph',
                                'title' => 'Check website'
                            ]
                        ]
                    ]
                ]
            ]
        ];


        return [
            'text' => 'Message reply from class instead of xml'
        ];
    }
}