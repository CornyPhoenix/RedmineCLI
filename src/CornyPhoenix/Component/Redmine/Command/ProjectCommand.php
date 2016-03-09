<?php

namespace CornyPhoenix\Component\Redmine\Command;

use Redmine\Client;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use CornyPhoenix\Component\Redmine\Model\Project;

class ProjectCommand extends Command
{

    const ACTION_LIST = 'list';
    const ACTION_SELECT = 'select';

    protected function configure()
    {
        $this
            ->setName('project')
            ->setDescription('List, select and edit projects on Redmine')
            ->addArgument('action', InputArgument::OPTIONAL, 'Action to perform with projects', self::ACTION_LIST)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $memberships = $this->redmine->getCurrentUser()->getMemberships();
        switch ($input->getArgument('action')) {
            case self::ACTION_LIST:
                foreach ($memberships as $membership) {
                    $id = $membership['project']['id'];
                    if ($this->getProject() && $this->getProject()->getId() === $id) {
                        $output->writeln('* <info>' . $membership['project']['name'] . '</info>');
                        continue;
                    }

                    $output->writeln('  ' . $membership['project']['name']);
                }
                break;

            case self::ACTION_SELECT:
                $projects = array_map(function ($arg) {
                    return $arg['project']['name'];
                }, $memberships);

                $question = new ChoiceQuestion('Select a project: ', $projects);
                $project = $this->getHelper('question')->ask($input, $output, $question);

                $id = null;
                foreach ($memberships as $membership) {
                    if ($membership['project']['name'] === $project) {
                        $id = $membership['project']['id'];
                        break;
                    }
                }

                // Retrieve the current project.
                $project = $this->redmine->getProjectRepository()->find($id);
                if (null === $project) {
                    throw new LogicException('Could not retrieve login ');
                }

                $this->redmine->setCurrentProject($project);
                break;
        }
    }
}