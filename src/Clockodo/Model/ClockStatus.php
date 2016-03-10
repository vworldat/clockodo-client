<?php

namespace Clockodo\Model;

class ClockStatus extends BaseModel
{
    protected $runningEntry;

    protected $projects = array();

    protected $services = array();

    protected $days = array();

    protected function init(array $data)
    {
        if (isset($data['running'])) {
            $this->runningEntry = new Entry($data['running']);
        }

        if (isset($data['projects'])) {
            foreach ($data['projects'] as $projectData) {
                $project = new Project($projectData);
                $this->projects[$project->getId()] = $project;
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
     * @return Project[]
     */
    public function getProjects()
    {
        return $this->projects;
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
     * Get flattened array containing both parent and child projects by id.
     *
     * @return Project[]
     */
    public function getAllProjects()
    {
        $projects = [];
        foreach ($this->getProjects() as $project) {
            $projects[$project->getId()] = $project;
            foreach ($project->getChildren() as $child) {
                $projects[$child->getId()] = $child;
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
