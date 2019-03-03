<?php namespace Filebase\Support;

use ArrayObject;
use FileBase\Document;
class Collection extends ArrayObject
{

    /**
     * toArray Method
     *
     * @return array
     */
    public function toArray() : array
    {
        $array=[];
        foreach($this->getArrayCopy() as $document)
        {
            if(is_object($document) && $document instanceof Document)
            {
                $array[]=$document->attr();
                continue;
            }
            $array[]=$document;
            
        }
        return $array;
    }
}