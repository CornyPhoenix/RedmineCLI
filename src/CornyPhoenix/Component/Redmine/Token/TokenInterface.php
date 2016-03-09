<?php

namespace CornyPhoenix\Component\Redmine\Token;


interface TokenInterface
{

    /**
     * @param string $url The URL of the Redmine endpoint.
     * @return \Redmine\Client
     */
    public function createClient($url);
}