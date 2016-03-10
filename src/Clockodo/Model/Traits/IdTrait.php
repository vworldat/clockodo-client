<?php
namespace Clockodo\Model\Traits;

trait IdTrait
{
    public function getId()
    {
        return (int) $this->getValue('id');
    }
}
