<?php

namespace CornyPhoenix\Component\Redmine;


use CornyPhoenix\Component\Redmine\Model\Project;
use CornyPhoenix\Component\Redmine\Model\User;
use CornyPhoenix\Component\Redmine\Token\ApiKeyToken;
use Redmine\Client;
use Symfony\Component\Serializer\Serializer;

class ConfigLoader
{

    /**
     * @var string
     */
    private $configFilename;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Redmine
     */
    private $redmine;

    /**
     * ConfigLoader constructor.
     * @param string $configFilename
     * @param Serializer $serializer
     * @param Redmine $redmine
     */
    public function __construct($configFilename, Serializer $serializer, Redmine $redmine)
    {
        $this->serializer = $serializer;
        $this->redmine = $redmine;
        $this->configFilename = $configFilename;
    }

    public function loadFromConfig()
    {
        // Load from config
        if (!file_exists($this->configFilename)) {
            return false;
        }

        $json = $this->serializer->decode(file_get_contents($this->configFilename), 'json');

        if (isset($json['api']) && isset($json['key'])) {
            $this->redmine->connect($json['api'], new ApiKeyToken($json['key']));
        }

        if (isset($json['project'])) {
            $this->redmine->setCurrentProject($this->serializer->denormalize($json['project'], Project::class));
        }

        if (isset($json['user'])) {
            $this->redmine->setCurrentUser($this->serializer->denormalize($json['user'], User::class));
        }

        return true;
    }

    public function saveToConfig()
    {
        $json = ['is_connected' => $this->redmine->isConnected()];

        // Save to config
        if ($this->redmine->isConnected()) {
            $json['api'] = $this->redmine->getEndpoint();
            $json['key'] = $this->redmine->getCurrentUser()->getApiKey();
            $json['project'] = $this->serializer->normalize($this->redmine->getCurrentProject());
            $json['user'] = $this->serializer->normalize($this->redmine->getCurrentUser());

            $json = $this->serializer->encode($json, 'json');

            if (false === file_put_contents($this->configFilename, $json)) {
                throw new \RuntimeException('Could not save application state to ~/.redmine');
            }
        }
    }
}