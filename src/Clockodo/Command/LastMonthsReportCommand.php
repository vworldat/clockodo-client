<?php

namespace Clockodo\Command;

use Clockodo\Report\MonthlyReportGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Clockodo\Model\GroupedEntry;
use Symfony\Component\Console\Helper\TableSeparator;

class LastMonthsReportCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('report:months')
            ->setDescription('Generate entries report for a single month')
            ->addArgument(
                'months',
                InputArgument::OPTIONAL,
                'Number of months to include. Defaults to 3',
                3
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->getApi();
        $reportGenerator = new MonthlyReportGenerator($api);

        $entries = $reportGenerator->getEntriesByUserAndProjectForMonths($input->getArgument('months'));

        $table = new Table($output);
        $table->setHeaders([
            'Month',
            'User',
            'Project',
            'Total time',
            'Note',
        ]);

        $first = true;
        foreach ($entries as $byMonth) {
            if (!$first) {
                $table->addRow(new TableSeparator());
            }
            $this->addResultRow($table, $byMonth, 'Month');

            foreach ($byMonth->getChildren() as $byUser) {
                $this->addResultRow($table, $byUser, 'User');

                foreach ($byUser->getChildren() as $byProject) {
                    $this->addResultRow($table, $byProject, 'Project');
                }
            }

            $first = false;
        }

        $table->render();
    }

    private function addResultRow(Table $table, GroupedEntry $entry, $groupField)
    {
        $data = [
            'Month' => '',
            'User' => '',
            'Project' => '',
            'Total time' => $entry->getDurationTime(),
            'Note' => $entry->getNote(),
        ];
        $data[$groupField] = $entry->getName() ? $entry->getName() : $entry->getGroup();

        //dump($data);
        $table->addRow($data);
    }
}
