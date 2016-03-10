<?php

namespace Clockodo\Model;

class Task extends BaseModel
{
    protected $day;

    public function __construct(array $data, Day $day = null)
    {
        parent::__construct($data);

        $this->day = $day;
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
    public function getDurationTime()
    {
        return $this->getValue('duration_time');
    }

    /**
     * @return string
     */
    public function getDurationText()
    {
        return $this->getValue('duration_text');
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getValue('customers_id');
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->getValue('customers_name');
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->getValue('projects_id');
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->getValue('projects_name');
    }

    /**
     * @return int
     */
    public function getServiceId()
    {
        return $this->getValue('services_id');
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->getValue('services_name');
    }

    /**
     * @return bool
     */
    public function isBillable()
    {
        return (boolean) $this->getValue('billable');
    }

    /**
     * @return int
     */
    public function getTextId()
    {
        return $this->getValue('text_id');
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->getValue('text');
    }

    /**
     * @return bool
     */
    public function isClocking()
    {
        return (boolean) $this->getValue('is_clocking');
    }

    /**
     * @return bool
     */
    public function hasJustLumpSums()
    {
        return (bool) $this->getValue('has_just_lumpSums');
    }
}
