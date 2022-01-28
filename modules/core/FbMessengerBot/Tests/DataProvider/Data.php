<?php

/**
 * This data provider contains mocked response data by
 * facebook messenger
 */

namespace FbMessengerBot\Tests\DataProvider;

use FbMessengerBot\Flow\Contracts\FlowInterface;

class Data
{
    const PAGE_ID = '102504434829001';

    const FB_ID = '2912335075508834';

    const PAGE_TOKEN = 'EAAutVasWkewBAGp5nKDZBUza0e6zn3oGfOZAwIGwwwhTDeaBg6jiZCfdt3wPgwMZASYLOvEtAYQ3tsrfX5649WOeBmpnV4taPfp23zYQtNOBjDvn5tbovtf8LhOZCW9Jk0dUu8Nl1IZCo2WjyRy0oNilUXa1GbBZCJK7fip3at8wpbQxIPxCrwsbQYKB12C4ioZD';

    public function messengerGetStarted()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'postback' => [
                                'title' => 'Get Started',
                                'payload' => json_encode([
                                    'type' => 'legacy_reply_to_message_action',
                                    'message' => 'Get Started',
                                    'action' => 'get_started'
                                ]),
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function pickColor()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Pick color',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => 'color_red'
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $wrongInput = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Yes'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data, $wrongInput)
        ];
    }

    public function createFlowFromClass()
    {
        
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Get Products',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => 'getProducts'
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function initiatedLiveChat()
    {
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
                                'title' => 'Talk to Customer Support',
                                'payload' => json_encode([
                                    'action' => 'live_support'
                                ])
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Hi, how are you? I would like to I inquire?'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data, $user_chat)
        ];
    }

    public function getProducts()
    {

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
                                'title' => 'Show Products',
                                'payload' => json_encode([
                                    'action' => 'show_products'
                                ])
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        return [
            [$data]
        ];
    }

    public function enterQuantityToBuy()
    {
        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => '2'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return [
            [$user_chat]
        ];
    }

    public function proceedToCheckout()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'No, Proceed to Checkout',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => 'show_order_summary'
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function shippingInfo()
    {
        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'John Doe'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return [
            [$user_chat]
        ];
    }

    public function payViaDragonpay()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Dragonpay',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => 'payment_dragonpay'
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }



    /**------------------------------------------------------------------------------------- */



    public function withChatReply()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'postback' => [
                                'title' => 'Get Started',
                                'payload' => json_encode([
                                    'type' => 'legacy_reply_to_message_action',
                                    'message' => 'Get Started',
                                    'action' => 'get_started'
                                ]),
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Yes'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data, $user_chat)
        ];
    }

    public function confirmed18YearsOld()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Yes',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::YES_18
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function below18YearsOld()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'No',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::NO_18
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Yes'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data, $user_chat)
        ];
    }

    public function dataPrivacy()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Data Privacy Policy',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::DATA_PRIVACY_POLICY
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'ldafjdla',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data, $user_chat)
        ];
    }

    public function brandList()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Product List',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::LIST_BRAND
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function showProducts()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'postback' => [
                                'title' => 'Show Products',
                                'payload' => json_encode([
                                    'action' => FlowInterface::SHOW_PRODUCTS,
                                    'brand_id' => 1,
                                ]),
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function addToCart()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593173683371',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593173683043',
                            'postback' => [
                                'title' => 'Add to cart',
                                'payload' => json_encode([
                                    'action' => FlowInterface::ADD_TO_CART,
                                    'id' => 1,
                                ]),
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function userReplyAddToCartQuantity()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => '3',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function noAddMore()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'No',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::NO_ADD_MORE
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function checkoutFirstQuestion()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Jeff Claud',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function checkoutAskEmailAddress()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'jefferson.claud@nuworks.ph',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function checkoutAskContactNumber()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => '09175864585',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function checkoutAskCity()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Quezon City',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::CHECKOUT_CITY,
                                        'title' => 'Quezon City'
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    
    public function customerFeedback()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Customer Feedback',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::CUSTOMER_FEEDBACK,
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'ldafjdla',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data, $user_chat)
        ];
    }

    public function chatGetStarted()
    {
        $user_chat = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'get started',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($user_chat)
        ];
    }

    public function faq()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'FAQ',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::FAQ,
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function faqHowToOrder()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'How To Order',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::FAQ_HOW_TO_ORDER,
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function faqPaymentOptions()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Payment Options',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::FAQ_PAYMENT_OPTIONS,
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function faqDeliveryTimeAndCoverage()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Delivery Time and Coverage',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::FAQ_DELIVERY_COVERAGE,
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return [
            array($data)
        ];
    }

    public function faqBottleAndShellDeposit()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Bottle and Case Deposit',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::FAQ_BOTTLE_AND_CASE_DEPOSIT,
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];

        return [
            array($data)
        ];
    }

    public function liveChat()
    {
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => self::PAGE_ID,
                    'time' => '1593307327417',
                    'messaging' => [
                        [
                            'sender' => [
                                'id'  => self::FB_ID
                            ],
                            'recipient' => [
                                'id' => self::PAGE_ID
                            ],
                            'timestamp' => '1593307327223',
                            'message' => [
                                'mid' => 'm_HuMfYyD16WwcEDqbD9340fSRWRj2dT7NNDhdYRHZfxuRllluG1mtHiNGVmD2dheOULHnS_tCcSXEOtOw3eyHnA',
                                'text' => 'Live Chat',
                                'quick_reply' => [
                                    'payload' => json_encode([
                                        'action' => FlowInterface::LIVE_CHAT,
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'handover_protocol' => [
                'primary_receiver' => '12345',
                'secondary_receiver' => '678910'
            ]
        ];

        return [
            array($data)
        ];
    }
}