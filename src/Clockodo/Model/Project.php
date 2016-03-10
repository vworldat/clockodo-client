<?php

namespace Clockodo\Model;

use Clockodo\Model\Traits\IdTrait;
use Clockodo\Model\Traits\NameTrait;

class Project extends BaseModel
{
    use IdTrait;
    use NameTrait;

    protected $children = array();
    protected $parent;

    public function __construct(array $data, $parent = null)
    {
        parent::__construct($data);

        $this->parent = $parent;
    }

    protected function init(array $data)
    {
        if (isset($data['projects'])) {
            foreach ($data['projects'] as $projectData) {
                $this->children[] = new Project($projectData, $this);
            }
        }
    }

    /**
     * @return bool
     */
    public function canAdd()
    {
        $access = $this->getValue('access');

        return (boolean) $access['add'];
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        $access = $this->getValue('edit');

        return (boolean) $access['edit'];
    }

    /**
     * @return Project[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return self
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get full name including parent name if available.
     *
     * @return string
     */
    public function getFullName()
    {
        if ($this->getParent()) {
            return $this->getParent()->getName().' - '.$this->getName();
        }

        return $this->getName();
    }

    /**
     * The customer id is the id of the main/parent project.
     *
     * @return string
     */
    public function getCustomerId()
    {
        if ($this->getParent()) {
            return $this->getParent()->getId();
        }

        return $this->getId();
    }
}
