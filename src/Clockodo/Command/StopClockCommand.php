<?php

namespace Clockodo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StopClockCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('clock:stop')
            ->setDescription('Stop a running clock')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->getApi();
        $status = $api->getClockStatus();

        $entry = $status->getRunningEntry();
        if (!$entry) {
            $output->writeln("\n<error>  Cannot stop clock: clock is not running  </error>\n");

            return;
        }

        $entry = $api->stopClock($entry);

        if (null !== $entry) {
            $output->writeln("<info>Clock stopped at {$entry->getTimeUntil()}. Total time was {$entry->getDurationTime()}</info>");
        } else {
            $output->writeln("<error>There was an error stopping the clock</error>");
        }
    }
}
