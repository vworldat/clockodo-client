<?php

namespace Clockodo\Model;

class Day extends BaseModel
{
    protected $tasks = array();

    protected function init(array $data)
    {
        foreach ($data['tasks'] as $taskData) {
            $this->tasks[] = new Task($taskData, $this);
        }
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->getValue('date');
    }

    /**
     * @return string
     */
    public function getDateText()
    {
        return $this->getValue('date_text');
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->getValue('duration');
    }

    /**
     * @return string
     */
    public function getDurationText()
    {
        return $this->getValue('duration_text');
    }

    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
