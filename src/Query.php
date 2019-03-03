<?php namespace Filebase;

use Filebase\{Table,Config,Document};
use Filebase\Support\Filesystem;
use Filebase\Support\Collection;

class Query 
{
    public $table;
    public $fs;
    public $formater;
    protected $conditions=[];

    /**
     * __construct function
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->fs = $this->getDb()->fs();

        // we have access to this within $this->db()->config()->format
        $this->formater = $this->getDb()->config()->formater;
        
    }

    /**
     * getTable function
     *
     * @return Table
     */
    public function getTable() : Table
    {
        return $this->table;
    }

    /**
     * getDb function
     *
     * @return Database
     */
    public function getDb() : Database
    {
        return $this->table->db();
    }

    /**
     * getConfig function
     *
     * @return Config
     */
    public function getConfig() : Config
    {
        return $this->getDb()->config(); 
    }

    /**
     * create function
     *
     * @param array $args
     * @return Document
     */
    public function create(array $args) : Document
    {
        // TODO:ADD START POINT FOR ID 
        // TODO:VALIDATE
        $name=$this->getTable()->genUniqFileId();
        $this->fs->write($this->getTable()->name().'/'.$name,$this->formater::encode($args,true));
        return $this->find($name);  
    }

    /**
     * findOrFail function
     *
     * @param [type] $id
     * @return void
     */
    public function findOrFail($id=null)
    {
        if($id===null)return false;

        $result=$this->find($id);
        return count($result)==0  ? false : $result;
    } 

    /**
     * find function
     *
     * @param [type] ...$id
     * @return Document|Collection
     */
    public function find(...$id)
    {
        // call findMany on find([1,2,3])
        if(is_array($id[0]))return $this->findMany(...$id[0]);
        // call findMany on find(1,2,3,) 
        if(count($id) > 1 ) return $this->findMany(...$id);

        // return single document on find(1)
        $ext=$this->getConfig()->extension;
        $id=strpos($id[0],'.'.$ext)!==false ? str_replace('.'.$ext,'',$id[0]) : $id[0];

        return $this->getDb()->fs()->has($this->getTable()->name().'/'.$id.'.'.$ext) ?
            (new Document($this->getTable(),
                                $id.'.'.$ext,
                                    $this->formater::decode($this->getDb()->fs()->read($this->getTable()->name().'/'.$id.'.'.$ext),true))):
                // empty Document on if item not exist 
                (new Document($this->getTable(),$id.'.'.$ext));
    }

    /**
     * findMany function
     *
     * @param [type] ...$ids
     * @return Collection
     */
    public function findMany(...$ids) : Collection
    {
        $ids=is_array($ids[0]) ? $ids[0] : $ids;
        $docs=[];
        foreach($ids as $id)
        {
            // if doc is true will store
            if($doc=$this->findOrFail($id)) $docs[]=$doc;
        }
        return new Collection($docs);
    }

    /**
    * Get a list of documents within our table
    * Returns an array of items
    *
    * @return Collection
    */
    public function getAll() : Collection
    {
        $items=$this->getDb()->fs()->files($this->getTable()->name(), $this->getConfig()->extension);
        $_items=[];
        foreach($items as $item)
        {
            $_items[]=new Document($this->getTable(),$item,json_decode(
                $this->getDb()->fs()->read($this->getTable()->name().DIRECTORY_SEPARATOR.$item.".".$this->getConfig()->extension)
            ,true));
        }
        return new Collection($_items);
    }

    /**
     * where function
     *
     * @param [type] ...$args
     * @return Query
     */
    public function where(...$args) : Query
    {
        // check if we are passing anonymous function
        // to create (A=X AND B=C) AND (C=X OR D=X)
        if (is_callable($args[0]))
        {
            // TODO: need to make this functional 
            // return $args[0]($this);
        }

        if(count($args)==1 && is_array($args[0]))
        {
             foreach($args[0] as $item)
             {
                list($key,$con,$value) = [$item[0],$item[1],$item[2]];
                $this->conditions['and'][$key] = [$con,$value];
             }

             return $this;
        }

        // check for multi array input
        $array = array_map(function($r){
            return is_array($r);
        },$args);

        if(array_product($array))
        {
            foreach($args as $item)
            {
                list($key,$con,$value) = [$item[0],$item[1],$item[2]];
                $this->conditions['and'][$key] = [$con,$value];
            }

            return $this;
        }

        // on normal request
        list($key,$con,$value) = $args;
        $this->conditions['and'][$key] = [$con,$value];

        return $this;
    }

    /**
     * This is an alias for where
     *
     * @param [type] ...$args
     * @return Query
     */
    public function andWhere(...$args) : Query
    {
        return $this->where(...$args);
    }

    /**
     * orWhere function
     *
     * @param [type] ...$args
     * @return Query
     */
    public function orWhere(...$args) : Query
    {
        if(count($args) ==1 && is_array($args[0]))
        {
             foreach($args[0] as $item)
             {
                list($key,$con,$value)=[$item[0],$item[1],$item[2]];
                $this->conditions['or'][]=[$key,$con,$value];
             }
             return $this;
        }
        // check for multi array input
        $array=array_map(function($r){
            return is_array($r);
        },$args);
        if(array_product($array))
        {
            foreach($args as $item)
            {
                list($key,$con,$value)=[$item[0],$item[1],$item[2]];
                $this->conditions['or'][]=[$key,$con,$value];
            }
            return $this;
        }
        // on normal request
        list($key,$con,$value)=$args;
        $this->conditions['or'][]=[$key,$con,$value];
        return $this;
    }

    /**
     * getConditions function
     *
     * @return array
     */
    public function getConditions() : array
    {
        return $this->conditions;
    }

    /**
     * get function
     *
     * @return Collection
     */
    public function get() : Collection
    {
        if(isset($this->conditions['and']))
        {
            return $this->filter();
        }

        return $this->getAll();
    }

    /**
     * filter function
     *
     * @return Collection
     */
    public function filter() : Collection
    {
        $items = $this->getAll();
        foreach($this->conditions['and'] as $and_condition_key=>$and_condition)
        {
            $result = [];
            foreach ($items as $key => $value) 
            {
                if(isset($value[$and_condition_key]))
                {
                    // run the AND clause
                    if($this->match($value[$and_condition_key],$and_condition[0],$and_condition[1]))
                    {
                        $result[] = $value;
                        continue;
                    }

                    // just if record rejected with 'and' conditions we need to match record with 'or' conditions
                    if(!isset($this->conditions['or'])) continue;

                    // run the OR clause
                    foreach ($this->conditions['or'] as $or_condition) 
                    {
                        if($this->match($value[$or_condition[0]],$or_condition[1],$or_condition[2]))
                        {
                            $result[] = $value;
                            continue;
                        }
                    }
                }

            }

            $items = array_unique($result);
        } 

        return new Collection(array_unique($result));
    }

    /**
     * match function
     *
     * @param [type] $key
     * @param [type] $operator
     * @param [type] $value
     * @return bool
     */
    public function match($key, $operator, $value) : bool
    {
        $operator=trim($operator);
        switch (true)
        {
            case ($operator === '=' && $key == $value):
                return true;
            case ($operator === '==' && $key == $value):
                return true;
            case ($operator === '===' && $key === $value):
                return true;
            case ($operator === '!=' && $key != $value):
                return true;
            case ($operator === '!==' && $key !== $value):
                return true;
            case (strtoupper($operator) === 'NOT' && $key != $value):
                return true;
            case ($operator === '>'  && $key >  $value):
                return true;
            case ($operator === '>=' && $key >= $value):
                return true;
            case ($operator === '<'  && $key <  $value):
                return true;
            case ($operator === '<=' && $key <= $value):
                return true;
            case ((strtoupper($operator) === 'LIKE' || strtoupper($operator) === 'CONTAIN') 
                                                    && preg_match('/'.$value.'/is',$key)):
                return true;
            case ((strtoupper($operator) === 'NOT LIKE' || str_replace(' ','',strtoupper($operator)) === '!LIKE') 
                                                    && !preg_match('/'.$value.'/is',$key)):
                return true;
            case (strtoupper($operator) === 'IN' && in_array($key, (array) $value)):
                return true;
            case (strtoupper($operator) === 'IN' && in_array($value, (array) $key)):
                return true;
            case (strtoupper($operator) === 'REGEX' && preg_match($value, $key)):
                return true;
            default:
                return false;
        }
    }

}