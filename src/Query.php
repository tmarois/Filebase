<?php namespace Filebase;

use Filebase\Table;
use Filebase\Support\Filesystem;
use Filebase\Support\Collection;
use Filebase\Format\Json;


class Query 
{
    public $table;
    public $fs;
    public $formater;
    protected $conditions=[];

    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->fs = new Filesystem($table->fullPath());

        // we have access to this within $this->db()->config()->format
        $this->formater = $this->db()->config()->formater;
        
    }

    public function table()
    {
        return $this->table;
    }

    public function db()
    {
        return $this->table->db();
    }
    public function config()
    {
        return $this->db()->config(); 
    }

    public function create(array $args)
    {
        // TODO:ADD START POINT FOR ID 
        // TODO:VALIDATE
        $name=$this->table()->genUniqFileId();
        $this->fs->write($name,$this->formater::encode($args,true));
        return $this->find($name);  
    }
    public function findOrFail($id=null)
    {
        if($id===null)return false;

        $result=$this->find($id);
        return count($result)==0  ? false : $result;
    } 
    public function find(...$id)
    {
        // call findMany on find([1,2,3])
        if(is_array($id[0]))return $this->findMany(...$id[0]);
        // call findMany on find(1,2,3,) 
        if(count($id) > 1 ) return $this->findMany(...$id);

        // return single document on find(1)
        $id=$id[0];
        $ext=$this->config()->extension;
        $id=strpos($id,'.'.$ext)!==false ? str_replace('.'.$ext,'',$id) : $id;

        return $this->fs->has($id.'.'.$ext) ?
            (new Document($this->table(),$id.'.'.$ext,(array)json_decode($this->fs->read($id.'.'.$ext),true))):
                // empty Document on if item not exist 
                (new Document($this->table(),$id.'.'.$ext));
    }
    public function findMany(...$ids)
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
    * @return array
    */
    public function getAll()
    {
        $items=$this->table()->fs()->files('.', $this->config()->extension);
        $_items=[];
        foreach($items as $item)
        {
            $_items[]=new Document($this->table(),$item,json_decode(
                $this->db()->fs()->read($this->table()->name().DIRECTORY_SEPARATOR.$item.".".$this->config()->extension)
            ,true));
        }
        return new Collection($_items);
    }
    public function where(...$args)
    {
        if(count($args)==1 && is_array($args[0]))
        {
             foreach($args[0] as $item)
             {
                list($key,$con,$value)=[$item[0],$item[1],$item[2]];
                $this->conditions['and'][$key]=[$con,$value];
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
                $this->conditions['and'][$key]=[$con,$value];
            }
            return $this;
        }
        // on normal request
        list($key,$con,$value)=$args;
        $this->conditions['and'][$key]=[$con,$value];
        return $this;
    }
    public function andWhere(...$args)
    {
        return $this->where(...$args);
    }
    public function orWhere(...$args)
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
    public function getConditions()
    {
        return $this->conditions;
    }
    public function get()
    {
        if(isset($this->conditions['and']))
        {
            return $this->filter();
        }
        return $this->getAll();
    }
    public function filter()
    {
        $items=$this->getAll();
        foreach($this->conditions['and'] as $and_condition_key=>$and_condition)
        {
            $result=[];
            foreach ($items as $key => $value) {
                if(isset($value[$and_condition_key]))
                {
                    if($this->match($value[$and_condition_key],$and_condition[0],$and_condition[1]))
                    {
                        $result[]=$value;
                        continue;
                    }
                    // just if record rejected with 'and' conditions we need to match record with 'or' conditions
                    if(!isset($this->conditions['or'])) continue;
                    foreach ($this->conditions['or'] as $or_condition) {
                        if($this->match($value[$or_condition[0]],$or_condition[1],$or_condition[2]))
                        {
                            $result[]=$value;
                            continue;
                        }
                    }
                }

            }
            $items=array_unique($result);
        } 
        return array_unique($result);
    }
    public function match($key, $operator, $value)
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