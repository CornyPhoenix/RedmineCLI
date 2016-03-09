<?php
/*
 * (C) 2016 ${USER_NAME}
 */

namespace CornyPhoenix\Component\Redmine;


use CornyPhoenix\Component\Redmine\Model\Project;
use CornyPhoenix\Component\Redmine\Model\User;
use Redmine\Client;
use Symfony\Component\Serializer\Serializer;

class ConfigLoader
{

    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var Application
     */
    private $application;

    /**
     * ConfigLoader constructor.
     * @param Serializer $serializer
     * @param Application $application
     */
    public function __construct(Serializer $serializer, Application $application)
    {
        $this->serializer = $serializer;
        $this->application = $application;
    }

    /**
     * @param string $filename
     */
    public function loadFromConfig($filename)
    {
        // Load from config
        if (file_exists($filename)) {
            $json = $this->serializer->decode(file_get_contents($filename), 'json');

            if (isset($json['api']) && isset($json['key'])) {
                $this->application->setClient(new Client($json['api'], $json['key']));
            }

            if (isset($json['project'])) {
                $this->application->setCurrentProject($this->serializer->denormalize($json['project'], Project::class));
            }

            if (isset($json['user'])) {
                $this->application->setCurrentUser($this->serializer->denormalize($json['user'], User::class));
            }
        }
    }

    /**
     * @param string $filename
     */
    public function saveToConfig($filename)
    {
        // Save to config
        if (null !== $this->application->getClient()) {
            $client = $this->application->getClient();
            $json = [
                'api' => $client->getUrl(),
                'key' => $this->application->getCurrentUser()->getApiKey(),
                'project' => $this->serializer->normalize($this->application->getCurrentProject()),
                'user' => $this->serializer->normalize($this->application->getCurrentUser()),
            ];

            $json = $this->serializer->encode($json, 'json');

            if (false === file_put_contents($filename, $json)) {
                throw new \RuntimeException('Could not save application state to ~/.redmine');
            }
        }
    }
}