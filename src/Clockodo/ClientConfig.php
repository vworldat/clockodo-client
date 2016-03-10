<?php

namespace Clockodo;

class ClientConfig
{
    private $apiUser;
    private $apiKey;

    /**
     * Create client config instance.
     *
     * @param string $apiUser
     * @param string $apiKey
     */
    public function __construct($apiUser, $apiKey)
    {
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;
    }

    /**
     * Get the API user (email).
     *
     * @return string
     */
    public function getApiUser()
    {
        return $this->apiUser;
    }

    /**
     * Get the API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
