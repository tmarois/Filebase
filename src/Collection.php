<?php 
namespace Filebase;

use ArrayObject;

class Collection extends ArrayObject
{

    public function toArray()
    {
        return $this->getArrayCopy();
    }
}