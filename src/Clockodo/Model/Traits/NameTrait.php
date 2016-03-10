<?php
namespace Clockodo\Model\Traits;

trait NameTrait
{
    public function getName()
    {
        return $this->getValue('name');
    }
}
