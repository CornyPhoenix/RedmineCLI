<?php

namespace CornyPhoenix\Component\Redmine\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('info')
            ->setDescription('Shows information about the tool and logged in user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->redmine->getCurrentUser();

        $output->writeln($this->getApplication()->getHelp());
        $output->writeln('');

        if (null !== $user) {
            $output->writeln(<<<HTML
Hi, <info>{$user->getFirstname()} {$user->getLastname()}</info>!
<comment>Endpoint: </comment> {$this->redmine->getEndpoint()}
<comment>API key:  </comment> {$user->getApiKey()}
<comment>Username: </comment> {$user->getLogin()}
HTML
            );
        }
    }
}