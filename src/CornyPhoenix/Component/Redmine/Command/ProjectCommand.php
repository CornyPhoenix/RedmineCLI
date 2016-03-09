<?php

namespace CornyPhoenix\Component\Redmine\Command;

use Redmine\Client;
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

    const LIST = 'list';
    const SELECT = 'select';

    protected function configure()
    {
        $this
            ->setName('project')
            ->setDescription('List, select and edit projects on Redmine')
            ->addArgument('action', InputArgument::OPTIONAL, 'Action to perform with projects', self::LIST)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $memberships = $this->getApplication()->getCurrentUser()->getMemberships();
        switch ($input->getArgument('action')) {
            case self::LIST:
                foreach ($memberships as $membership) {
                    $id = $membership['project']['id'];
                    if ($this->getProject() && $this->getProject()->getId() === $id) {
                        $output->writeln('* <info>' . $membership['project']['name'] . '</info>');
                        continue;
                    }

                    $output->writeln('  ' . $membership['project']['name']);
                }
                break;

            case self::SELECT:
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

                $projectArray = $this->getRedmine()->project->show($id)['project'];
                $project = $this->getApplication()->getSerializer()->denormalize($projectArray, Project::class);

                $this->getApplication()->setCurrentProject($project);

                break;
        }
    }
}