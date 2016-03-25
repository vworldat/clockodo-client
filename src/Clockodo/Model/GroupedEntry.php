<?php
namespace Clockodo\Model;

use Clockodo\Model\Traits\NameTrait;

class GroupedEntry extends BaseModel
{
    use NameTrait;

    protected $children = array();

    protected function init(array $data)
    {
        if (isset($data['sub_groups'])) {
            foreach ($data['sub_groups'] as $groupData) {
                $child = new static($groupData);
                $this->children[$child->getGroup()] = $child;
            }
        }
    }

    /**
     * @return Project[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getGroupedBy()
    {
        return $this->getValue('groupedBy');
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->getValue('group');
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
     * Get applied grouping restrictions
     *
     * @return string[]
     */
    public function getRestrictions()
    {
        return $this->getValue('restrictions');
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return trim(str_replace("\r", '', $this->getValue('note')));
    }
}
