<?php

namespace Clockodo\Command;

use Clockodo\Model\ClockStatus;
use Clockodo\Model\Project;
use Clockodo\Model\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class StartClockCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('clock:start')
            ->setDescription('Start the clock, providing information about the current task')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->getApi();
        $status = $api->getClockStatus();

        if ($status->getRunningEntry()) {
            $output->writeln("\n<error>  Cannot start clock: clock is already running  </error>\n");

            return;
        }

        $project = $this->askProject($status, $input, $output);
        $service = $this->askService($status, $input, $output);
        $text = $this->askText($status, $input, $output);
        $billable = $this->askBillable($status, $input, $output);

        $entry = $api->startClock($project, $service, $billable, $text);

        $output->writeln("<info>Clock started at {$entry->getTimeSince()}</info>");
    }

    /**
     * Fetch project to clock in.
     *
     * @param ClockStatus $status
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return Project
     */
    protected function askProject(ClockStatus $status, InputInterface $input, OutputInterface $output)
    {
        $task = $status->getLatestTask();
        $projects = $status->getAllProjectsForAdding();

        $projectNames = array_map(function (Project $project) use ($task) {
            $name = $project->getFullName().' :: '.$project->getId();
            if ($task->getProjectId() == $project->getId()) {
                $name = "\xE2\x80\xA2 $name";
            } else {
                $name = "  $name";
            }

            return $name;
        }, $projects);

        $default = isset($projectNames[$task->getProjectId()]) ? $projectNames[$task->getProjectId()] : 0;
        $projectNames = array_values($projectNames);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Select project: ',
            $projectNames,
            $default
        );
        $question->setErrorMessage('Project %s is invalid.');

        $project = $helper->ask($input, $output, $question);
        $output->writeln("\n<info>You have selected: ".$project."</info>\n");

        list(, $id) = explode(' :: ', $project, 2);

        return $projects[$id];
    }

    /**
     * Fetch service to clock in.
     *
     * @param ClockStatus $status
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return Service
     */
    protected function askService(ClockStatus $status, InputInterface $input, OutputInterface $output)
    {
        $task = $status->getLatestTask();
        $services = $status->getServices();

        $serviceNames = array_map(function (Service $service) use ($task) {
            $name = $service->getName().' :: '.$service->getId();
            if ($task->getServiceId() == $service->getId()) {
                $name = "\xE2\x80\xA2 $name";
            } else {
                $name = "  $name";
            }

            return $name;
        }, $services);

        $default = isset($serviceNames[$task->getServiceId()]) ? $serviceNames[$task->getServiceId()] : 0;
        $serviceNames = array_values($serviceNames);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Select service: ',
            $serviceNames,
            $default
        );
        $question->setErrorMessage('Service %s is invalid.');

        $service = $helper->ask($input, $output, $question);
        $output->writeln("\n<info>You have selected: ".$service."</info>\n");

        list(, $id) = explode(' :: ', $service, 2);

        return $services[$id];
    }

    /**
     * Fetch task text.
     *
     * @param ClockStatus $status
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function askText(ClockStatus $status, InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question(
            'Task description: ',
            true
        );
        $question->setAutocompleterValues($status->getTaskTexts());

        $text = $helper->ask($input, $output, $question);

        $output->writeln("\n<info>Text: ".$text."</info>\n");

        return $text;
    }

    /**
     * Fetch billable flag.
     *
     * @param ClockStatus $status
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function askBillable(ClockStatus $status, InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Billable? [Y/n]: ',
            true
        );

        $billable = $helper->ask($input, $output, $question);

        $output->writeln("\n<info>You have selected: ".($billable ? 'yes':'no')."</info>\n");

        return $billable;
    }
}
