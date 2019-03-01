<?php 

namespace Filebase;

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
    * @param Filebase\Table
    * @param string $name
    * @param array $attr
    */
    public function __construct(Table $table, $name, array $attr=[])
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
        $format = $this->db()->config()->format;

        $data = $format::encode($data);

        return $this->db()->fs()->write($this->name(), $data);
    }

    /**
    * Delete the document
    *
    * @return boolean
    */
    public function delete()
    {
        return $this->db()->fs()->delete($this->table->name().DIRECTORY_SEPARATOR.$this->name());
    }

    /**
    * Magic GET method into our data
    * This allows the dev to quickly access data variables
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
}
