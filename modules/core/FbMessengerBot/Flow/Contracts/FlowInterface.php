<?php


namespace FbMessengerBot\Flow\Contracts;

use FbMessengerBot\HttpClient\Server;

interface FlowInterface
{
    
    const TEMPLATE_GENERICE = 'generic';

    const GET_STARTED = 'get_started';

    const DATA_PRIVACY_POLICY = 'data_privacy_policy';

    const FROM_DATA_PRIVACY_GO_BACK = 'from_dpa_go_back';

    const YES_18 = 'yes_18';

    const NO_18 = 'no_18';

    const AGREE_TO_DPA = 'agree_to_dpa';

    const DISAGREE_TO_DPA = 'disagree_to_dpa';

    const UNCONFIRMED_18 = 'unconfirmed_18';

    const LIST_BRAND = 'list_brand';

    const FAQ = 'faq';

    const CUSTOMER_FEEDBACK = 'customer_feedback';

    const SYSTEM_RESPONSE_TO_CUST_FEEDBACK = 'system_response_to_customer_feedback';

    const SHOW_PRODUCTS = 'show_products';

    const ADD_TO_CART = 'add_to_cart';

    const VALIDATE_PRODUCT_QTY_INPUT = 'validate_product_qty_input';

    const AFTER_INPUT_CORRECT_QTY = 'after_input_correct_qty';

    const YES_ADD_MORE = 'yes_add_more';

    const NO_ADD_MORE = 'no_add_more';

    const CHECKOUT_REMAINING_QUESTION = 'checkout_remaining_question';

    const CHECKOUT_VALIDATE_EMAIL_ADDRESS = 'checkout_validate_email_address';

    const CHECKOUT_CITY = 'city';

    const CITY_OTHERS = 'city_others';

    const DRAGON_PAY = 'dragonpay';

    const LIVE_CHAT = 'live_chat';

    const FAQ_HOW_TO_ORDER = 'how_to_order';

    const FAQ_PAYMENT_OPTIONS = 'payment_options';

    const FAQ_DELIVERY_COVERAGE = 'delivery_coverage';

    const FAQ_BOTTLE_AND_CASE_DEPOSIT = 'bottle_and_case_deposit';

    const FAQ_GO_BACK = 'fa_go_back';
    
    const EXIT_LIVE_CHAT = 'exit_live_chat';

    /**
     * Get response that will be send back to messenger
     *
     * @param \FbMessengerBot\HttpClient\Server $server
     * 
     * @return array
     */
    public function getResponse(Server $server): array;

    /**
     * Noop method.
     *
     * @param \FbMessengerBot\HttpClient\Server $server
     * 
     * @return null
     */
    public function setUserNextAction(Server $server);

    /**
     * Handover protocol
     * 
     * Primary receiver pass the control to secondary receiver
     *
     * @return bool
     */
    public function passThreadControl();

    /**
     * Handover protocol
     * 
     * Primary receiver take back control from secondary receiver
     *
     * @return bool
     */
    public function takeThreadControl();
}