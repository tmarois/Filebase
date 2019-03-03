<?php namespace Filebase\Support;

use ArrayObject;

class Collection extends ArrayObject
{

    /**
     * Return the collection as an array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /**
     * Count all the items in the collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->toArray());
    }

    /**
     * Filter the array using the given callback.
     *
     * @param  callable  $callback
     * 
     * @return array
     */
    public function filter(callable $callback)
    {
        return new static(array_filter($this->toArray(), $callback, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Merge the collection with the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function merge($array)
    {
        return new static(array_merge($this->toArray(), $array));
    }
}