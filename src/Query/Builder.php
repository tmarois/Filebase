<?php  namespace Filebase\Query;

use Exception;
use Filebase\Table;
use Filebase\Document;
use Filebase\Query\Results;
use Filebase\Query\Predicate;

class Builder
{

    /**
    * $table
    *
    * @var Filebase\Table
    */
    protected $table;


    /**
    * $fields
    *
    * @var array
    */
    protected $fields  = [];


    /**
    * $limit
    *
    * @var int
    */
    protected $limit  = 0;


    /**
    * $offset
    *
    * @var int
    */
    protected $offset  = 0;


    /**
    * $orderBy
    *
    * @var string
    */
    protected $orderBy = '';


    /**
    * $sortBy
    *
    * @var string
    */
    protected $sortBy = 'ASC';


    /**
    * $predicate
    *
    * @var Filebase\Query\Predicate
    */
    protected $predicate;


    /**
    * $predicate
    *
    * @var Filebase\Query\Predicate
    */
    public $useGroup = [];


    /**
    * __construct
    *
    * @param Filebase\Table $table
    *
    */
    public function __construct(Table $table)
    {
        $this->table = $table;

        $this->predicate = new Predicate();
    }


    /**
    * predicate()
    *
    * @return Filebase\Query\Predicate
    */
    public function predicate()
    {
        return $this->predicate;
    }


    /**
    * table()
    *
    * @return Filebase\Table
    */
    public function table()
    {
        return $this->table;
    }


    /**
    * select()
    *
    * Only return selected fields
    *
    * @param mixed $fields
    *
    * @return Filebase\Query\Builder
    */
    public function select($fields)
    {
        if (is_string($fields))
        {
            $fields = explode(',',trim($fields));
        }

        if (is_array($fields))
        {
            $this->fields = $fields;
        }

        return $this;
    }


    /**
    * where()
    *
    * Set up a where query
    *
    * @param mixed $arg
    *
    * @return Filebase\Query\Builder
    */
    public function where(...$arg)
    {
        $this->predicate()->add('and', $arg);

        return $this;
    }


    /**
    * limit()
    *
    * Set the query limit and offset
    *
    * @param int $limit
    * @param int $offset
    *
    * @return Filebase\Query\Builder
    */
    public function limit($limit, $offset = 0)
    {
        $this->limit = (int) $limit;

        if ($this->limit === 0)
        {
            $this->limit = 9999999;
        }

        $this->offset = (int) $offset;

        return $this;
    }


   /**
   * orderBy()
   *
   * Set the order by and sort by fields
   *
   * @param string $orderByField
   * @param string $sortDirection
   *
   * @return Filebase\Query\Builder
   */
   public function orderBy($orderByField, $sortDirection = 'ASC')
   {
       $this->orderBy = $orderByField;

       $this->sortBy = $sortDirection;

       return $this;
   }


   /**
   * get()
   *
   * Query builder results
   *
   * @return Filebase\Query\Results
   */
   public function get()
   {
       return (new Results($this))->get();
   }


   /**
   * results()
   *
   * Get the results from the query
   *
   * @return Filebase\Query\Results
   */
   public function results()
   {
       return $this->get()->results();
   }


   /**
   * results()
   *
   * Get the results from the query
   *
   * @return Filebase\Query\Results
   */
   public function count()
   {
       return $this->get()->count();
   }


   /**
   * results()
   *
   * Get the results from the query
   *
   * @return Filebase\Query\Results
   */
   public function first()
   {
       return $this->get()->first();
   }

}
