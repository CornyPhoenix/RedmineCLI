<?php

namespace CornyPhoenix\Component\Redmine\Command;

use CornyPhoenix\Component\Redmine\Model\User;
use Redmine\Client;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ApiCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('api')
            ->setDescription('Connect to Redmine API')
            ->addArgument('url', InputArgument::REQUIRED, 'URL of the Redmine API')
            ->addOption('key', 'k', InputOption::VALUE_OPTIONAL, 'API key for Redmine')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $key = $this->getApiKey($input, $output, $helper);
        if (null === $key) {
            $question = new Question('<question> Username </question> ');
            $username = $helper->ask($input, $output, $question);

            $question = new Question('<question> Password </question> ');
            $question->setHidden(true);
            $password = $helper->ask($input, $output, $question);

            $client = new Client($url, $username, $password);
        } else {
            $client = new Client($url, $key);
        }

        $this->getApplication()->setClient($client);

        $user = $client->user->getCurrentUser()['user'];
        if ($client->user->lastCallFailed()) {
            throw new \RuntimeException('API key was wrong! Could not connect to ' . $client->getUrl());
        }

        $user = $this->getApplication()->getSerializer()->denormalize($user, User::class);
        $this->getApplication()->setCurrentUser($user);

        $firstname = $this->getApplication()->getCurrentUser()->getFirstname();
        $lastname = $this->getApplication()->getCurrentUser()->getLastname();
        $output->writeln("Welcome, <info>$firstname $lastname</info>!");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $helper
     * @return string|null
     */
    private function getApiKey(InputInterface $input, OutputInterface $output, $helper)
    {
        if (null !== $input->getOption('key')) {
            return $input->getOption('key');
        }

        $question = new Question('<question> Redmine API key </question> (none) ');
        $key = $this->getHelper('question')->ask($input, $output, $question);

        return $key;
    }
}