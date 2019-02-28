<?php namespace Filebase;

/**
 * The document class
 * 
 * This class access the document data
 * and functionality 
 * 
 */
class Document
{
    /**
    * The database table 
    *
    * @var Filebase\Table
    */
    protected $tb;

    /**
    * Document name
    *
    * @var string
    */
    protected $name;

    /**
    * Document path
    *
    * @var string
    */
    protected $path;

    /**
    * Document data
    *
    * @var array
    */
    protected $data = [];

    /**
    * Start up the table class
    *
    * @param string $name
    */
    public function __construct($tb, $name)
    {
        $this->tb = $tb;

        // TODO: We need to validate the name of this document
        // names should be lowercased and be parsed to use underscores
        $this->name = $name;

        $this->path = $this->table()->path().'/'.$this->name;
    }

    /**
    * This is easy access to our table
    *
    * @return Filebase\Table
    */
    public function table()
    {
        return $this->tb;
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
    * Get our document name (id)
    *
    * @return string
    */
    public function name()
    {
        return $this->name;
    }

    /**
    * Get the document path
    *
    * @return string
    */
    public function path()
    {
        return $this->path;
    }

    /**
    * Get the document data
    *
    * @return array
    */
    public function data()
    {
        return $this->data;
    }

    /**
    * Set the document data
    * This will replace all existing data
    *
    * @return array
    */
    public function set($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
    * Write document data into file
    *
    * @return array
    */
    public function save()
    {
        $format = $this->db()->config()->format;

        $data = $format::encode($data);

        return $this->db()->fs()->write($this->path(), $data);
    }

    /**
    * Delete the document
    *
    * @return boolean
    */
    public function delete()
    {
        return $this->db()->fs()->delete($this->path());
    }


    /**
    * Magic GET method into our data
    * This allows the dev to quickly access data variables
    *
    * @return mixed
    */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    /**
    * Get our data as normal array
    *
    * @return array
    */
    public function toArray()
    {
        return $this->data;
    }

    /**
    * Get our data as a JSON string
    *
    * @return string
    */
    public function toJson()
    {
        return json_encode($this->data);
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
}
