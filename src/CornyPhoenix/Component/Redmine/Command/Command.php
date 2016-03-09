<?php
/*
 * (C) 2016 ${USER_NAME}
 */

namespace CornyPhoenix\Component\Redmine\Command;

use Redmine\Client;
use Symfony\Component\Console\Command\Command as BaseCommand;
use CornyPhoenix\Component\Redmine\Application;

class Command extends BaseCommand
{

    /**
     * @return Application
     */
    public function getApplication()
    {
        return parent::getApplication();
    }

    /**
     * @return Client
     */
    protected function getRedmine()
    {
        return $this->getApplication()->getClient();
    }

    /**
     * @return \CornyPhoenix\Component\Redmine\Model\User
     */
    protected function getUser()
    {
        return $this->getApplication()->getCurrentUser();
    }

    /**
     * @return \CornyPhoenix\Component\Redmine\Model\Project
     */
    protected function getProject()
    {
        return $this->getApplication()->getCurrentProject();
    }
}