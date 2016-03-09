<?php

namespace CornyPhoenix\Component\Redmine;

use Redmine\Client;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;
use CornyPhoenix\Component\Redmine\Command\ApiCommand;
use CornyPhoenix\Component\Redmine\Command\ProjectCommand;
use CornyPhoenix\Component\Redmine\Model\Project;
use CornyPhoenix\Component\Redmine\Model\User;

class Application extends BaseApplication
{

    const NAME = 'Redmine CLI';
    const VERSION = '0.0.1';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var User
     */
    private $currentUser;

    /**
     * @var Project
     */
    private $currentProject;

    /**
     * @var ConfigLoader
     */
    private $configLoader;

    /**
     * Application constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        parent::__construct(self::NAME, self::VERSION);

        $this->serializer = $serializer;
        $this->configLoader = new ConfigLoader($serializer, $this);

        $this->configLoader->loadFromConfig(self::getConfigFilename());
        $this->initCommands();
    }

    /**
     * Application destructor.
     */
    public function __destruct()
    {
        $this->configLoader->saveToConfig(self::getConfigFilename());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);

        $formatter = $output->getFormatter();

        $style = new OutputFormatterStyle(null, null, array('bold'));
        $formatter->setStyle('strong', $style);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
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

    protected function initCommands()
    {
        $this->add(new ApiCommand());
        $this->add(new ProjectCommand());
    }

    /**
     * @return string
     */
    public static function getConfigFilename()
    {
        return $configFile = getenv('HOME') . '/.redmine';
    }
}