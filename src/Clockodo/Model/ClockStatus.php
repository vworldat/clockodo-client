<?php

namespace Clockodo\Model;

class ClockStatus extends BaseModel
{
    protected $runningEntry;

    protected $customers = array();

    protected $services = array();

    protected $days = array();

    protected function init(array $data)
    {
        if (isset($data['running'])) {
            $this->runningEntry = new Entry($data['running']);
        }

        if (isset($data['projects'])) {
            foreach ($data['projects'] as $customerData) {
                $customer = new Customer($customerData);
                $this->customers[$customer->getId()] = $customer;
            }
        }

        if (isset($data['services'])) {
            foreach ($data['services'] as $serviceData) {
                $service = new Service($serviceData);
                $this->services[$service->getId()] = $service;
            }
        }

        if (isset($data['list'])) {
            foreach ($data['list'] as $dayData) {
                $day = new Day($dayData);
                $this->days[$day->getDate()] = $day;
            }
        }
    }

    /**
     * @return Entry|null
     */
    public function getRunningEntry()
    {
        return $this->runningEntry;
    }

    /**
     * @return Customer[]
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @return Service[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return Day[]
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Get latest committed task.
     *
     * @return Task
     */
    public function getLatestTask()
    {
        foreach ($this->getDays() as $day) {
            foreach ($day->getTasks() as $task) {
                return $task;
            }
        }
    }

    /**
     * Get latest task texts
     *
     * @return string[]
     */
    public function getTaskTexts()
    {
        $texts = [];
        foreach ($this->getDays() as $day) {
            foreach ($day->getTasks() as $task) {
                $texts[] = $task->getText();
            }
        }

        return $texts;
    }

    /**
     * Get flattened array containing both customers and projects by id.
     *
     * @return Project[]
     */
    public function getAllProjects()
    {
        $projects = [];
        foreach ($this->getCustomers() as $customer) {
            $projects[$customer->getId()] = $customer;
            foreach ($customer->getChildren() as $project) {
                $projects[$project->getId()] = $project;
            }
        }

        return $projects;
    }

    /**
     * Get all projects that are available for adding entries.
     *
     * @return Project[]
     */
    public function getAllProjectsForAdding()
    {
        return array_filter($this->getAllProjects(), function (Project $project) {
            return $project->canAdd();
        });
    }
}
