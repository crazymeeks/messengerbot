<?php

namespace FbMessengerBot\UserProfile;

use Ixudra\Curl\CurlService;

class FacebookUserProfile
{

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
     * @var \Ixudra\Curl\CurlService
     */
    protected $curl;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $userFacebookId;

    /**
     * @var string
     */
    protected $requestedFields;

    public function __construct(CurlService $curl)
    {
        $this->curl = $curl;
    }

    /**
     * Set facebook token
     *
     * @param string $token
     * 
     * @return $this
     */
    public function setToken(string $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Set user facebook id
     *
     * @param string $userFacebookId
     * 
     * @return $this
     */
    public function setUserFacebookId(string $userFacebookId)
    {
        $this->userFacebookId = $userFacebookId;
        return $this;
    }

    /**
     * The requested user profile fields allowed by facebook
     * or in requested permission
     * 
     * @param array $fields
     *
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->requestedFields = implode(',', $fields);
        return $this;
    }

    /**
     * Get facebook token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get user facebook id
     *
     * @return string
     */
    public function getUserFacebookId()
    {
        return $this->userFacebookId;
    }

    /**
     * Get request fields
     *
     * @return string
     */
    public function getFields()
    {
        return $this->requestedFields;
    }

    public function get()
    {
        $primary_token = $this->getToken();
        $response = $this->curl->to($this->graph_uri . '/' . $this->getUserFacebookId())
                               ->withHeader('Authorization: Bearer ' . $primary_token)
                               ->withData([
                                   'qs' => [
                                       'access_token' => $primary_token,
                                       'fields' => $this->getFields()
                                   ]
                               ])
                               ->withResponseHeaders()
                               ->returnResponseObject()
                               ->get();

        return $response;
    }

}