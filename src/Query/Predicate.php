<?php  namespace Filebase\Query;


use Exception;

class Predicate
{

    /**
    * $allowed_operators
    *
    * Allowed operators within the query
    */
    protected $allowed_operators = [
        '=',
        '==',
        '===',
        '!=',
        '!==',
        '>',
        '<',
        '>=',
        '<=',
        'IN',
        'NOT',
        'LIKE',
        'NOT LIKE',
        'REGEX'
    ];


    /**
    * $predicates
    *
    * Query clauses
    */
    protected $predicates = [];


    /**
    * add
    *
    */
    public function add($logic, $arg)
    {
        if (!is_array($arg))
        {
            throw new Exception('Predicate Error: argument passed must be type of array');
        }

        if (count($arg) == 1)
        {
            if (isset($arg[0]) && is_array($arg[0]))
            {
                foreach($arg[0] as $key => $value)
                {
                    if ($value == '') continue;

                    $arg = $this->format($key, $value);
                }
            }
        }

        if (count($arg) != 3)
        {
            throw new Exception('Predicate Error: Must have 3 arguments passed - '.count($arg).' given');
        }

        if (!in_array($arg[1], $this->allowed_operators))
        {
            throw new Exception('Predicate Error: Unknown Operator '.$arg[1]);
        }

        $arg[0] = trim($arg[0]);

        if ($arg[0] == '')
        {
            throw new Exception('Field name can not be empty');
        }

        $this->predicates[$logic][] = $arg;
    }


    /**
    * format
    *
    */
    protected function format($key, $value)
    {
        return [$key,'==',$value];
    }


    /**
    * get
    *
    */
    public function get()
    {
        return array_filter($this->predicates);
    }

}
