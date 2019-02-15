<?php  namespace Filebase;

/**
 * Recursive sort logic class
 *
 * @package Filebase
 */
class SortLogic
{
    /**
     * Field names to sort by
     *
     * @var array
     */
    public $orderBy = [];

    /**
     * Sort direction (ASC, DESC)
     *
     * @var array
     */
    public $sortDirection = [];

    /**
     * Index of the current sort data
     *
     * @var int
     */
    public $index = 0;

    /**
     * Constructor
     *
     * @param array $orderBy
     * @param array $sortDirection
     * @param int $index
     * @return void
     */
    public function __construct($orderBy, $sortDirection, $index = 0)
    {
        $this->orderBy = $orderBy;
        $this->sortDirection = $sortDirection;
        $this->index = $index;
    }

    /**
     * Sorting callback
     *
     * @param Document $docA
     * @param Document $docB
     * @return return int (-1, 0, 1)
     */
    public function sort($docA, $docB)
    {
        $propA = $docA->field($this->orderBy[$this->index]);
        $propB = $docB->field($this->orderBy[$this->index]);

        if (strnatcasecmp($propA, $propB) == 0)
        {
            if (!isset($this->orderBy[$this->index + 1]))
            {
                return 0;
            }

            // If they match and there are multiple orderBys, go deeper (recurse)
            $sortlogic = new self($this->orderBy, $this->sortDirection, $this->index + 1);
            return $sortlogic->sort($docA, $docB);
        }

        if ($this->sortDirection[$this->index] == 'DESC')
        {
            return strnatcasecmp($propB, $propA);
        }
        else
        {
            return strnatcasecmp($propA, $propB);
        }
    }
}
