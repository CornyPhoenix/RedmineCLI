<?php

namespace CornyPhoenix\Component\Redmine\Command;

use CornyPhoenix\Component\Redmine\Model\User;
use CornyPhoenix\Component\Redmine\Token\ApiKeyToken;
use CornyPhoenix\Component\Redmine\Token\UsernamePasswordToken;
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
        // Retrieve login information
        $url = $input->getArgument('url');
        $token = $this->createLoginToken($input, $output);

        // Connect to redmine server and retrieve login
        $this->redmine->connect($url, $token);
        $this->redmine->updateCurrentUser();

        // Display welcome message
        $user = $this->redmine->getCurrentUser();
        $name = $user->getFirstname() . ' ' . $user->getLastname();
        $output->writeln("Hi, <info>$name</info>!");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return ApiKeyToken|UsernamePasswordToken
     */
    protected function createLoginToken(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $apiKey = $input->getOption('key');
        if (null === $apiKey) {
            $question = new Question('<question>Username:</question> ');
            $username = $helper->ask($input, $output, $question);

            $question = new Question('<question>Password:</question> ');
            $question->setHidden(true);
            $password = $helper->ask($input, $output, $question);

            $token = new UsernamePasswordToken($username, $password);
            return $token;
        } else {
            $token = new ApiKeyToken($apiKey);
            return $token;
        }
    }
}