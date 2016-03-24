<?php

namespace Clockodo\Model;

class Customer extends Project
{
    protected $children = array();

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function init(array $data)
    {
        if (isset($data['projects'])) {
            foreach ($data['projects'] as $projectData) {
                $child = new Project($projectData, $this);
                $this->children[$child->getId()] = $child;
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
     * Get full name including parent name if available.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getName();
    }

    /**
     * The customer id is the id of the main/parent project.
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getId();
    }
}
