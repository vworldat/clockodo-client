<?php
namespace Clockodo;

use Clockodo\Model\ClockStatus;
use Clockodo\Model\Entry;
use Clockodo\Model\GroupedEntry;
use Clockodo\Model\Project;
use Clockodo\Model\Service;

class Api
{
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return ClockStatus
     */
    public function getClockStatus()
    {
        $data = $this->client->getResource('clock', 'update');

        return new ClockStatus($data);
    }

    /**
     * Start the clock with the given parameters.
     *
     * @param Project $project
     * @param Service $service
     * @param bool    $billable
     * @param string  $text
     */
    public function startClock(Project $project, Service $service, $billable, $text = null)
    {
        $result = $this->client->postResource('clock', null, [
            'customers_id' => $project->getCustomerId(),
            'services_id' => $service->getId(),
            'billable' => (int) $billable,
            'projects_id' => $project->getId(),
            'text' => $text,
        ]);

        return new Entry($result['running']);
    }

    /**
     * Stop running of the given entry.
     *
     * @param Entry $entry
     * @return Entry
     */
    public function stopClock(Entry $entry)
    {
        $result = $this->client->deleteResource('clock', $entry->getId());
        if (isset($result['stopped'])) {
            $entry = new Entry($result['stopped']);

            return $entry;
        }
    }

    /**
     * Get grouped entries for the given date range.
     *
     * Possible values to use in $groupedBy are:
     * - customers_id
     * - projects_id
     * - services_id
     * - users_id
     * - texts_id
     * - billable
     * - is_lumpSum
     * - year
     * - week
     * - month
     * - day
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param array     $groupedBy
     */
    public function getGroupedEntries(\DateTime $startDate, \DateTime $endDate, array $groupedBy)
    {
        $result = $this->client->getResource(
            'entrygroups',
            null,
            [
                'time_since' => $startDate->format(static::DATETIME_FORMAT),
                'time_until' => $endDate->format(static::DATETIME_FORMAT),
                'grouping' => $groupedBy,
            ]
        );

        $grouped = [];
        foreach ($result['groups'] as $data) {
            $grouped[] = new GroupedEntry($data);
        }

        return $grouped;
    }

    /**
     * Get entries for the given date range.
     *
     * TODO: filtering and pagination
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return Entry[]
     */
    public function getEntries(\DateTime $startDate, \DateTime $endDate)
    {
        $result = $this->client->getResource(
            'entries',
            null,
            [
                'time_since' => $startDate->format(static::DATETIME_FORMAT),
                'time_until' => $endDate->format(static::DATETIME_FORMAT),
            ]
        );

        $entries = [];
        foreach ($result['entries'] as $data) {
            $entries[] = new Entry($data);
        }

        return $entries;
    }
}
