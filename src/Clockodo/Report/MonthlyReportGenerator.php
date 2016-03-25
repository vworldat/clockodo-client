<?php

namespace Clockodo\Report;

use Clockodo\Api;
use Clockodo\Model\GroupedEntry;

class MonthlyReportGenerator
{
    /**
     * @var Api
     */
    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Get grouped entries by user and project for the given month offset.
     *
     * @param number $offset
     * @return GroupedEntry[]
     */
    public function getEntriesByUserAndProjectForMonth($offset = 0)
    {
        $startDate = new \DateTime('first day of this month');
        $startDate->setTime(0, 0);

        $endDate = clone $startDate;
        $endDate->modify('+1 month');

        $offset = (int) $offset;
        if (0 !== $offset) {
            $startDate->modify("$offset months");
            $endDate->modify("$offset months");
        }

        return $this->api->getGroupedEntries($startDate, $endDate, ['users_id', 'projects_id']);
    }

    /**
     * Get grouped entries by month, user and project for the last n months.
     *
     * @param int $months
     * @return GroupedEntry[]
     */
    public function getEntriesByUserAndProjectForMonths($months = 3)
    {
        $startDate = new \DateTime('first day of this month');
        $startDate->setTime(0, 0);

        $endDate = clone $startDate;
        $endDate->modify('+1 month');

        $months--;
        $startDate->modify("-$months months");

        return $this->api->getGroupedEntries($startDate, $endDate, ['month', 'users_id', 'projects_id']);
    }
}
