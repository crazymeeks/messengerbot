<?php

/**
 * This file defines the next reply for each actions
 * in messenger. The array key is the action taken
 * and the value is the expected reply by our
 * server. For example, our server will
 * respond after_get_started once
 * user click get started
 * on messenger
 *

use FbMessengerBot\Flow\Contracts\FlowInterface;


return [
    FlowInterface::GET_STARTED => [
        \FbMessengerBot\Flow\AfterGetStarted::class,
        \FbMessengerBot\Flow\TakeThreadControl::class,
    ],
    FlowInterface::YES_18 => [
        \FbMessengerBot\Flow\DisplayDpa::class,
    ],
    FlowInterface::AGREE_TO_DPA => [
        \FbMessengerBot\Flow\Options\ListBrandOption::class
    ],
    FlowInterface::DISAGREE_TO_DPA => [
        \FbMessengerBot\Flow\DisagreeDpa::class,
    ],
    FlowInterface::NO_18 => [
        \FbMessengerBot\Flow\ForbiddenUnderAge::class,
    ],
    FlowInterface::DATA_PRIVACY_POLICY => [
        \FbMessengerBot\Flow\DataPrivacyPolicy::class,
    ],
    FlowInterface::LIST_BRAND => [
        \FbMessengerBot\Flow\Options\AfterListBrandOption::class,
        \FbMessengerBot\Flow\ListBrand::class,
    ],
    FlowInterface::SHOW_PRODUCTS => [
        \FbMessengerBot\Flow\ShowProducts::class,
    ],
    FlowInterface::ADD_TO_CART => [
        \FbMessengerBot\Flow\AskQuestion\QuestionDesiredProductQuantity::class,
    ],
    FlowInterface::YES_ADD_MORE => [
        // \FbMessengerBot\Flow\Options\AfterListBrandOption::class,
        \FbMessengerBot\Flow\ListBrand::class,
    ],
    FlowInterface::NO_ADD_MORE => [
        \FbMessengerBot\Flow\OrderSummary::class,
        \FbMessengerBot\Flow\AskQuestion\Checkout\AskName::class,
    ],
    FlowInterface::CUSTOMER_FEEDBACK => [
        \FbMessengerBot\Flow\CustomerFeedback::class,
    ],

    FlowInterface::LIVE_CHAT => [
        \FbMessengerBot\Flow\EnterLiveChat::class,
        // \FbMessengerBot\Flow\PassThreadControl::class,
    ],

    FlowInterface::EXIT_LIVE_CHAT => [
        \FbMessengerBot\Flow\TakeThreadControl::class,
    ],

    // Ask city in checkout, if user check others
    // send message that this location is not covered
    FlowInterface::CITY_OTHERS => [
        \FbMessengerBot\Flow\AskQuestion\Checkout\CityOthers::class,

    ],

    // FAQ
    FlowInterface::FAQ => [
        \FbMessengerBot\Flow\Faq\Start::class,
    ],
    FlowInterface::FAQ_HOW_TO_ORDER => [
        \FbMessengerBot\Flow\Faq\HowToOrder::class,
    ],
    FlowInterface::FAQ_PAYMENT_OPTIONS => [
        \FbMessengerBot\Flow\Faq\PaymentOptions::class,
    ],
    FlowInterface::FAQ_DELIVERY_COVERAGE => [
        \FbMessengerBot\Flow\Faq\DeliveryTimeAndCoverage::class
    ],
    FlowInterface::FAQ_BOTTLE_AND_CASE_DEPOSIT => [
        \FbMessengerBot\Flow\Faq\BottleAndShellDeposit::class,
    ],
    FlowInterface::FAQ_GO_BACK => [
        \FbMessengerBot\Flow\Options\ListBrandOption::class
    ],
    // End FAQ
    
];*/