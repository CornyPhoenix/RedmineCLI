<?php

namespace CornyPhoenix\Component\Redmine\Token;

use Redmine\Client;

class UsernamePasswordToken implements TokenInterface
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * UsernamePasswordToken constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param string $url
     * @return Client
     */
    public function createClient($url)
    {
        return new Client($url, $this->username, $this->password);
    }
}