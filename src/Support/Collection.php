<?php namespace Filebase\Support;

use ArrayObject;

class Collection extends ArrayObject
{

    public function toArray()
    {
        return $this->getArrayCopy();
    }

    public function count()
    {
        return count($this->getArrayCopy());
    }
}