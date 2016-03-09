<?php
/*
 * (C) 2016 ${USER_NAME}
 */

namespace CornyPhoenix\Component\Redmine\Command;

use CornyPhoenix\Component\Redmine\Redmine;
use Symfony\Component\Console\Command\Command as BaseCommand;
use CornyPhoenix\Component\Redmine\Application;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Serializer\Serializer;

class Command extends BaseCommand
{

    /**
     * @var Redmine
     */
    protected $redmine;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @param BaseApplication|null $application
     */
    public function setApplication(BaseApplication $application = null)
    {
        if (!$application instanceof Application) {
            throw new RuntimeException('Application must be a Redmine application');
        }

        parent::setApplication($application);
        $this->redmine = $application->getRedmine();
        $this->serializer = $application->getSerializer();
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return parent::getApplication();
    }

    /**
     * @return \CornyPhoenix\Component\Redmine\Model\User
     */
    protected function getUser()
    {
        return $this->redmine->getCurrentUser();
    }

    /**
     * @return \CornyPhoenix\Component\Redmine\Model\Project
     */
    protected function getProject()
    {
        return $this->redmine->getCurrentProject();
    }
}