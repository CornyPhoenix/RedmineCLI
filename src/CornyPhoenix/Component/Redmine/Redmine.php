<?php

namespace CornyPhoenix\Component\Redmine;

use CornyPhoenix\Component\Redmine\Model\Project;
use CornyPhoenix\Component\Redmine\Model\User;
use CornyPhoenix\Component\Redmine\Repository\ProjectRepository;
use CornyPhoenix\Component\Redmine\Token\TokenInterface;
use Redmine\Client;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Serializer\Serializer;

class Redmine
{

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var TokenInterface|null
     */
    private $token;

    /**
     * @var boolean
     */
    private $connected = false;

    /**
     * @var Client|null
     */
    private $client;

    /**
     * @var User|null
     */
    private $currentUser;
    /**
     * @var Project|null
     */
    private $currentProject;

    /**
     * Redmine constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    protected function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->token = null;
        $this->client = null;
        $this->connected = false;

        return $this;
    }

    /**
     * @return TokenInterface
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * @return User
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * @param User $user
     */
    public function setCurrentUser(User $user = null)
    {
        $this->currentUser = $user;
    }

    /**
     * @return Project
     */
    public function getCurrentProject()
    {
        return $this->currentProject;
    }

    /**
     * @param Project|int $project
     * @return $this
     */
    public function setCurrentProject(Project $project = null)
    {
        $this->currentProject = $project;

        return $this;
    }

    /**
     * Connects to Redmine.
     *
     * @param string $url
     * @param TokenInterface $token
     */
    public function connect($url, TokenInterface $token)
    {
        $this->setEndpoint($url);
        $this->token = $token;
        $this->client = $token->createClient($url);
        $this->connected = true;
    }

    /**
     * Disconnects from Redmine.
     */
    public function disconnect()
    {
        $this->setEndpoint(null);
    }

    public function updateCurrentUser()
    {
        $query = $this->client->user->getCurrentUser();
        $user = $query['user'];

        if ($this->client->user->lastCallFailed()) {
            throw new RuntimeException('API key was wrong! Could not connect to ' . $this->getEndpoint());
        }

        $this->currentUser = $this->serializer->denormalize($user, User::class);
    }

    /**
     * @return ProjectRepository
     */
    public function getProjectRepository()
    {
        return new ProjectRepository($this->client, $this->serializer);
    }
}