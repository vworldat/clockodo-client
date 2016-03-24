<?php

namespace Clockodo\Command;

use Clockodo\Model\ClockStatus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('status')
            ->setDescription('Show account/clock status overview')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->getApi();
        $output->writeln("\n<info>Fetching status information ...</info>");
        $output->writeln("================================");
        $status = $api->getClockStatus();

        $this->listRunningClock($status, $output);
        $this->listProjects($status, $output);
        $this->listServices($status, $output);
        $this->listDays($status, $output);
    }

    protected function listRunningClock(ClockStatus $status, OutputInterface $output)
    {
        $output->writeln("\n<info>Clock information</info>");
        $entry = $status->getRunningEntry();
        if (null === $entry) {
            $output->writeln("  <comment>Currently there is no running clock</comment>");

            return;
        }

        $customer = $status->getCustomers()[$entry->getCustomerId()];
        $projects = $customer->getChildren();
        $project = null;
        if (null !== $entry->getProjectId() && isset($projects[$entry->getProjectId()])) {
            $project = $projects[$entry->getProjectId()];
        }
        $service = $status->getServices()[$entry->getServiceId()];

        $table = new Table($output);
        $table->setHeaders([
            'Date',
            'Start',
            'Duration',
            'Task',
            'Customer',
            'Project',
            'Service',
        ]);

        list($date, $time) = explode(' ', $entry->getTimeSince());
        $table->addRow([
            $date,
            $time,
            preg_replace('/^00:/', '', $entry->getDurationTime()),
            $entry->getText(),
            $customer->getName(),
            $project ? $project->getName() : '',
            $service->getName(),
        ]);
        $table->render();
    }

    protected function listProjects(ClockStatus $status, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders([
            'ID',
            'CustomerID',
            'Name',
        ]);

        foreach ($status->getAllProjectsForAdding() as $project) {
            $table->addRow([
                $project->getId(),
                $project->getCustomerId(),
                $project->getFullName(),
            ]);
        }

        $output->writeln("\n<info>Available projects</info>");
        $table->render();
    }

    protected function listServices(ClockStatus $status, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders([
            'ID',
            'Name',
        ]);

        foreach ($status->getServices() as $service) {
            $table->addRow([
                $service->getId(),
                $service->getName(),
            ]);
        }

        $output->writeln("\n<info>Available services</info>");
        $table->render();
    }

    protected function listDays(ClockStatus $status, OutputInterface $output)
    {
        $projects = $status->getAllProjects();
        $services = $status->getServices();

        $table = new Table($output);
        $table->setHeaders([
            'Date',
            'Duration',
            'Task',
            'Customer',
            'Project',
            'Service',
        ]);

        foreach ($status->getDays() as $day) {
            $table->addRow([
                $day->getDate(),
                str_replace('&nbsp;', '', $day->getDurationText()),
                '',
            ]);

            foreach ($day->getTasks() as $task) {
                $table->addRow([
                    '',
                    str_replace('&nbsp;', '', $task->getDurationText()),
                    $task->getText(),
                    $projects[$task->getCustomerId()]->getName(),
                    $projects[$task->getProjectId()]->getName(),
                    $services[$task->getServiceId()]->getName(),
                ]);
            }
        }

        $output->writeln("\n<info>Last days</info>");
        $table->render();
    }
}
