<?php

namespace CornyPhoenix\Component\Redmine\Repository;


use Redmine\Client;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractRepository
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * ProjectRepository constructor.
     * @param Client $client
     * @param Serializer $serializer
     */
    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }
}