<?php

namespace Clockodo\Model;

abstract class BaseModel
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->init($data);
    }

    protected function init(array $data)
    {

    }

    /**
     * Get data value.
     *
     * @param string $name
     * @param mixed $default
     */
    public function getValue($name, $default = null)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }
}
