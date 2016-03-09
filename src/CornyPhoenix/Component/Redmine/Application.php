<?php

namespace CornyPhoenix\Component\Redmine;

use CornyPhoenix\Component\Redmine\Command\InfoCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;
use CornyPhoenix\Component\Redmine\Command\ApiCommand;
use CornyPhoenix\Component\Redmine\Command\ProjectCommand;

class Application extends BaseApplication
{

    const NAME = 'Redmine CLI';
    const VERSION = '0.0.2';

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ConfigLoader
     */
    private $configLoader;

    /**
     * @var Redmine
     */
    private $redmine;

    /**
     * Application constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        parent::__construct(self::NAME, self::VERSION);

        $this->redmine = new Redmine($serializer);
        $this->serializer = $serializer;
        $this->configLoader = new ConfigLoader(getenv('HOME') . '/.redmine', $this->serializer, $this->redmine);

        $this->configLoader->loadFromConfig();
        $this->initCommands();
    }

    /**
     * Application destructor.
     */
    public function __destruct()
    {
        $this->configLoader->saveToConfig();
        $this->redmine->disconnect();
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @return Redmine
     */
    public function getRedmine()
    {
        return $this->redmine;
    }

    /**
     * @return ConfigLoader
     */
    public function getConfigLoader()
    {
        return $this->configLoader;
    }

    public function getHelp()
    {
        $help = parent::getHelp();
        $help .= PHP_EOL;
        $help .= "Copyright (c) 2016 Konstantin Simon Maria Möllers";

        return $help;
    }

    /**
     * Initializes commands of this tool.
     */
    protected function initCommands()
    {
        $this->add(new ApiCommand());
        $this->add(new ProjectCommand());
        $this->add(new InfoCommand());
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
}