<?php namespace Filebase\Support;

use ArrayObject;

class Collection extends ArrayObject
{

    public function toArray()
    {
        return $this->getArrayCopy();
    }
}