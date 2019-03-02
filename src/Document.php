<?php 

namespace Filebase;

use ArrayAccess;
use Countable;
use ArrayObject;
/**
 * The document class
 * 
 * This class access the document data
 * and functionality 
 * 
 */
class Document implements ArrayAccess,Countable
{
    /**
     * The database table 
     *
     * @var Filebase\Table
     */
    protected $table;

    /**
     * Document path
     *
     * @var string
     */
    protected $path;

    /**
     * Document name
     *
     * @var string
     */
    protected $name;

    /**
     * Document data
     *
     * @var array
     */
    protected $attr = [];

    /**
     * Start up the table class
     *
     * @param Filebase\Table $table 
     * @param string         $name 
     * @param array          $attr 
     */
    public function __construct(Table $table, $name, array $attr = [])
    {
        // assign our table
        $this->table = $table;

        // assign our document name
        $this->name = $name;

        // TODO: We need to validate the attr of this document
        // attrs should be lowercased and be parsed to use underscores
        $this->attr = $attr;
    }

    /**
     * Get a count of data props in our document
     *
     * @return int
     */
    public function count()
    {
        return count($this->attr);
    }

    /**
     * This is easy access to our table
     *
     * @return Filebase\Table
     */
    public function table()
    {
        return $this->table;
    }

    /**
     * This is easy access to our database
     *
     * @return Filebase\Database
     */
    public function db()
    {
        return $this->table()->db();
    }

    /**
     * Get our document attr (data)
     *
     * @return string
     */
    public function attr()
    {
        return $this->attr;
    }

    /**
     * Get the document name
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Set the document attr (data)
     * This will replace all existing data
     *
     * @param array $data 
     * 
     * @return array
     */
    public function set(array $data = [])
    {
        $this->attr = $data;
        return $this;
    }

    /**
     * Write document data into file
     *
     * @return array
     */
    public function save()
    {
        // $format = $this->db()->config()->format;

        // $data = $format::encode($this->attr);
        $data=json_encode($this->attr);
        $this->table()->db()->fs()->put($this->table()->name().DIRECTORY_SEPARATOR.$this->name(), $data);
        // $this=$this->table()->get ($this->name());
        return $this; 
    }

    /**
     * Delete the document
     *
     * @return boolean
     */
    public function delete()
    {
        return $this->db()->fs()->delete(
            $this->table->name().DIRECTORY_SEPARATOR.$this->name()
        );
    }

    /**
     * Bulk Update items in the data prop
     *
     * @param array $args 
     * 
     * @return void
     */
    public function update(array $args)
    {
        foreach($args as $key=>$item)
        {
            if(isset($this->attr[$key]))
            {
                $this->attr[$key]=$item;
            }
        }

        $this->save();
    }

    /**
     * Magic __GET method into our data
     * This allows the dev to quickly access data variables
     *
     * @param string $key 
     * 
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->attr[$key])) {
            return $this->attr[$key];
        }
    }

    /**
     * Magic __SET method 
     * This will allow the dev to save into the data prop
     *
     * @param string $key 
     * @param string $value 
     * 
     * @return this
     */
    public function __set($key, $value)
    {
        $this->attr[$key] = $value;
        return $this;
    }

    /**
     * Magic __ISSET method 
     * Allows ability to isset() prop exist
     *
     * @param string $key 
     * 
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->attr[$key]);
    }

    /**
     * Magic __UNSET method 
     * Allows ability to unset() prop
     *
     * @param string $key 
     * 
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attr[$key]);
    }

    /**
     * Get our data as normal array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attr;
    }

    /**
     * Get our data as a JSON string
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->attr);
    }

    /**
     * If the document is being output as a string 
     * Let's force it to be shown as JSON
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }


    public function offsetSet($offset, $value) 
    {
        if (is_null($offset)) {
            $this->attr[] = $value;
        } else {
            $this->attr[$offset] = $value;
        }
    }

    public function offsetExists($offset) 
    {
        return isset($this->attr[$offset]);
    }

    public function offsetUnset($offset) 
    {
        unset($this->attr[$offset]);
    }

    public function offsetGet($offset) 
    {
        return isset($this->attr[$offset]) ? $this->attr[$offset] : null;
    }
}
