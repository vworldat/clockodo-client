<?php

namespace Clockodo\Model;

use Clockodo\Model\Traits\IdTrait;

class Entry extends BaseModel
{
    use IdTrait;

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
     * @return int
     */
    public function getOffset()
    {
        return $this->getValue('offset');
    }

    /**
     * @return string
     */
    public function getOffsetTime()
    {
        return $this->getValue('offset_time');
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getValue('customers_id');
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->getValue('projects_id');
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->getValue('users_id');
    }

    /**
     * @return int
     */
    public function getServiceId()
    {
        return $this->getValue('services_id');
    }

    /**
     * @return bool
     */
    public function isBillable()
    {
        return (boolean) $this->getValue('billable');
    }

    /**
     * @return bool
     */
    public function isBilled()
    {
        return (boolean) $this->getValue('billed');
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
     * @return string
     */
    public function getTimeSince()
    {
        return $this->getValue('time_since');
    }

    /**
     * @return string
     */
    public function getTimeUntil()
    {
        return $this->getValue('time_until');
    }

    /**
     * @return bool
     */
    public function isClocked()
    {
        return (boolean) $this->getValue('clocked');
    }

    /**
     * @return bool
     */
    public function isClocking()
    {
        return (boolean) $this->getValue('clocking');
    }

    /**
     * @return string
     */
    public function getLumpSump()
    {
        return (boolean) $this->getValue('lumpSum');
    }
}
