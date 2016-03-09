<?php

namespace CornyPhoenix\Component\Redmine\Token;

use Redmine\Client;

class ApiKeyToken implements TokenInterface
{

    /**
     * @var string
     */
    private $apiKey;

    /**
     * UsernamePasswordToken constructor.
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $url
     * @return Client
     */
    public function createClient($url)
    {
        return new Client($url, $this->apiKey);
    }
}