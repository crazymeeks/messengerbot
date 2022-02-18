<?php

/**
 * This class is responsible for sending reply back
 * to messenger.
 * 
 * (c) Jeff Claud
 */

namespace FbMessengerBot\HttpClient;

use App\Models\NextFlow;
use App\Models\FacebookFlow;
use Illuminate\Http\Request;
use Ixudra\Curl\CurlService;
use App\Models\Repositories\NextFlowRepository;
use FbMessengerBot\UserProfile\FacebookUserProfile;
use Crazymeeks\MessengerBot\Builder\FlowBuilder;

class Server
{

    /**
     * Default flow key defined in xml flow
     * 
     * @var const
     */
    const DEFAULT_FLOW = 'default';

    const LIVE_SUPPORT = 'live_support';

    /**
     * Indicator that the user initiated live chat
     * 
     * If this is on, user may type anything in the chatbox unless
     * they ended chat by themselves by typing "get started" or "end chat"
     * Admin may also end live chat
     */
    const ON_LIVE_SUPPORT_MODE = 'on_live_support_mode';

    /**
     * Facebook page id
     *
     * @var string
     */
    private $page_id;

    /**
     * Facebook user id
     *
     * @var string
     */
    private $fb_id;

    /**
     * Facebook user recipient id
     *
     * @var string
     */
    private $recipient_id;

    /**
     * Messenger postback payload
     *
     * @var \stdClass
     */
    private $postback_payload;

    /**
     * Facebook graph uri
     *
     * @var string
     */
    private $graph_uri = 'https://graph.facebook.com';

    /**
     * Facebook graph version
     *
     * @var string
     */
    private $graph_version = 'v7.0';


    /**
     * Next flow that will be send by our server to facebook
     *
     * @var \FbMessengerBot\Flow\Contracts\FlowInterface[]
     */
    private $next_flows;

    /**
     * Http curl client
     *
     * @var \Ixudra\Curl\CurlService $curl
     */
    private $curl;

    /**
     * Data container
     *
     * @var array
     */
    private $container = [];

    /**
     * User facebook firstname
     *
     * @var string
     */
    private $firstname;

    /**
     * User facebook lastname
     * 
     * @var string
     */
    private $lastname;

    /**
     * User facebook picture
     *
     * @var string
     */
    private $picture = null;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var \Crazymeeks\MessengerBot\Builder\FlowBuilder
     */
    protected $flowBuilder;

    /**
     * Constructor
     *
     * @param \Ixudra\Curl\CurlService $curl
     * @param \Crazymeeks\MessengerBot\Builder\FlowBuilder $flowBuilder
     */
    public function __construct(CurlService $curl, FlowBuilder $flowBuilder)
    {
        $this->curl = $curl;
        $this->flowBuilder = $flowBuilder;
    }

    /**
     * Set Facebook Page Id
     *
     * @param string $page_id
     * 
     * @return $this
     */
    public function setPageId(string $page_id)
    {
        $this->page_id = $page_id;

        return $this;
    }

    /**
     * Set user's facebook id
     *
     * @param string $fb_id
     * 
     * @return $this
     */
    public function setUserFacebookId(string $fb_id)
    {
        $this->fb_id = $fb_id;
        
        return $this;
    }

    /**
     * Set recipient id of messenger user
     *
     * This is the person where we send the response
     * 
     * @param string $recipient_id
     * 
     * @return $this
     */
    public function setRecipientId(string $recipient_id)
    {
        $this->recipient_id = $recipient_id;

        return $this;
    }

    /**
     * Set payload passed by facebook messenger
     *
     * @param \stdClass $payload
     * 
     * @return $this
     */
    public function setPostBackPayload(\stdClass $payload)
    {
        $this->postback_payload = $payload;

        return $this;
    }

    /**
     * Get messenger postback payload
     *
     * @return \stdClass
     */
    public function getPostBackPayload()
    {
        return $this->postback_payload;
    }

    /**
     * Get facebook user recipient id
     *
     * @return string
     */
    public function getRecipientId()
    {
        return $this->recipient_id;
    }

    /**
     * Get facebook page id
     *
     * @return string
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * Get facebook user id
     *
     * @return string
     */
    public function getUserFacebookId()
    {
        return $this->fb_id;
    }

    /**
     * Flow class builder
     * 
     * @param \stdClass $payload Payload from facebook response
     *
     * @return $this
     */
    public function createFlow(\stdClass $payload)
    {
        
        $fb_flow = new FacebookFlow();

        $flow = $fb_flow->findOne();
        
        $flow_array = $this->flowBuilder->decodeXMLMarkUp($flow->flow);

        // $xml = simplexml_load_string($flow->flow, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        // $flow_array = json_decode(json_encode($xml), TRUE);
        
        $action = $this->getAction($flow_array);

        $this->checkChatMode($action, $flow_array);
        $facebookToken = $this->getRequest()->header('fbpritoken');

        // transform xml
        $transformed = $this->flowBuilder
                            ->setFacebookToken($facebookToken)
                            ->setPostBackPayload($this->getPostBackPayload())
                            ->setRecipientId($this->getRecipientId())
                            ->transform($flow_array);
        
        $this->saveNextFlow($transformed)
             ->when(function($me) use($action){
                if ($action == \FbMessengerBot\Flow\Contracts\FlowInterface::GET_STARTED) {
                   $payload = $me->getPostBackPayload();
                   $next_flow = app(NextFlowRepository::class);
                   $next_flow->deleteUserFlow($payload->fb_id);
               }
            });

        return $transformed;
    }

    public function when(\Closure $callback)
    {
        call_user_func_array($callback, [$this]);

        return $this;
    }

    /**
     * Check chat mode if user is in live chat
     *
     * @param string $action
     * @param array $flow_array
     * 
     * @return $this
     */
    private function checkChatMode(string $action, &$flow_array)
    {
        
        if ($action == self::ON_LIVE_SUPPORT_MODE) {
            $flow_array = [];
        } else {
            if (isset($flow_array['next'])) {
                $flow_array = array_merge($flow_array[$action]['bot'], ['next' => $flow_array['next']]);
            } else {
                $flow_array = $flow_array[$action]['bot'];
            }
        }

        
        return $this;
    }

    /**
     * Get action.
     *
     * @return string
     */
    private function getAction(array $flow)
    {
        $payload = $this->getPostBackPayload();
        
        if (!isset($flow[$payload->action])) {
            // get next action from db
            
            $next_flow = new NextFlow();
            $result = $next_flow->findOne(['bot_user_id' => $this->getUserFacebookId()]);
            
            if ($result) {
                return $result->next;
            }
            
            return self::DEFAULT_FLOW;

        }

        return $payload->action;
    }

    /**
     * Save next flow to database.
     * 
     * This flow may be a validation of user's required next flow
     *
     * @return $this
     */
    public function saveNextFlow(array $flow = [])
    {
        
        if (count($flow) <= 0) {
            return $this;
        }

        $next_flow = new NextFlow();
        $payload = $this->getPostBackPayload();
        
        if ($payload->action == self::LIVE_SUPPORT) {
            $next_flow->saveNextFlow(self::ON_LIVE_SUPPORT_MODE, $payload->fb_id);
            return $this;
        }

        // is user is in live chat mode ?
        $result = $next_flow->findOne([
            'bot_user_id' => $this->getUserFacebookId(),
            'next' => self::ON_LIVE_SUPPORT_MODE
        ]);
        
        if ($result) {
            return $this;
        }
        
        if ($this->flowBuilder->hasNextFlow()) {
            
            $next_flow->saveNextFlow($this->flowBuilder->getNextFlow(), $payload->fb_id);
            return $this;
        }
        return $this;
    }

    /**
     * Reset specific data in a collection
     * 
     * This most likely happen when user issue get started chat
     *
     * @return void
     */
    public function resetSpecifics()
    {
        $payload = $this->getPostBackPayload();

        $shipping = new \App\Models\ShippingDetails();
        $shipping->deleteOne(['user_id' => $payload->fb_id]);

        $cart = new \App\Models\Cart();
        $cart->deleteMany(['user_id' => $payload->fb_id]);

        $nextFlow = new \App\Models\NextFlow();
        $nextFlow->deleteOne(['bot_user_id' => $payload->fb_id]);
    }

    /**
     * Execute call to Facebook api to send reply back to messenger
     *
     * @return array
     */
    public function execute()
    {
        
        $next_flows = $this->next_flows;
        
        $response = [];
        foreach($next_flows as $key => $flow){
            
            if ($key == 'message') {

                if (isset($flow['class']) && is_array($flow['class'])) {
                    foreach($flow['class'] as $object){
                        $responseData = [
                            'recipient' => [
                                'id' => $this->getRecipientId(),
                            ],
                            'message' => $object->getResponse($this)
                        ];
                        $response[] = $responseData;
                    }
                } else {
                    $responseData = [
                        'recipient' => [
                            'id' => $this->getRecipientId(),
                        ],
                        'message' => isset($flow['class']) ? ($flow['class'])->getResponse($this) : $flow
                    ];

                    $response[] = $responseData;
                }
            }
            unset($flow);
        }
        
        return $response;
    }

    public function getFacebookProfile()
    {

        $retrievedProfile = $this->flowBuilder->retrievedFacebookProfile();        

        $this->setFirstName($retrievedProfile->first_name)
             ->setLastName($retrievedProfile->last_name)
             ->setPicture($retrievedProfile->picture);
        return $this;
        
    }

    public function setFirstName(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function setLastName(string $lastname = null)
    {
        $this->lastname = $lastname;

        return $this;
    }


    public function getFirstName()
    {
        return $this->firstname;
    }

    public function getLastName()
    {
        return $this->lastname;
    }

    public function setPicture(string $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }


    /**
     * Get facebook graph uri
     *
     * @return string
     */
    public function getFbGraphUri()
    {
        return $this->graph_uri;
    }

    /**
     * Get facebook graph version
     *
     * @return string
     */
    public function getFbGraphVersion()
    {
        return $this->graph_version;
    }

    /**
     * Set request
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }


    /**
     * Get request
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}