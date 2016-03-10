<?php
namespace Clockodo;

use Clockodo\Model\ClockStatus;
use Clockodo\Model\Project;
use Clockodo\Model\Service;
use Clockodo\Model\Entry;

class Api
{
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
}
