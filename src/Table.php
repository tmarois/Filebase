<?php namespace Filebase;

use Filebase\Database;
use Filebase\Query;

/**
 * The table class
 * 
 * This class access the table
 * functionality and methods
 * 
 */
class Table
{

    /**
    * Database 
    *
    * @var Filebase\Database
    */
    protected $db;

    /**
    * Table name (directory)
    *
    * @var string
    */
    protected $name;

    /**
    * Table name path
    *
    * @var string
    */
    protected $path;

    /**
    * Start up the table class
    *
    * @param string $name
    */
    public function __construct(Database $db, $name)
    {
        $this->db = $db;

        // TODO: We need to validate the name of this table
        // names should be lowercased and be parsed to use underscores

        $this->name = $this->validateTableName($name);
        $this->path = DIRECTORY_SEPARATOR.$this->name;

        // if this directory (table) does not exist
        // lets automatically create it
        $this->validateTable();
    }

    /**
    * This is easy access to our database
    *
    * @return Filebase\Database
    */
    public function db()
    {
        return $this->db;
    }

    /**
    * Get our table name (id)
    *
    * @return string
    */
    public function name()
    {
        return $this->name;
    }

    /**
    * Get our table path (directory location within db root)
    *
    * @return string
    */
    public function path()
    {
        return $this->path;
    }

    /**
    * Get the full path of the table directory
    *
    * @return string
    */
    public function fullPath()
    {
        return $this->db()->config()->path . $this->path();
    }

    /**
    * Get a single document within this table
    *
    * @param string $name
    * @return Filebase\Document
    */
    public function get($name)
    {
        if($this->db()->fs()->has($name.'.json'))
        {
            return new Document($this, $name,json_decode($this->db()->fs()->read($this->name.'/'.$name.'.json')));
        }
        return new Document($this, $name,[]);
    }

    /**
    * Get a list of documents within our table
    * Returns an array of items
    *
    * @return array
    */
    public function getAll()
    {
        return $this->db()->fs()->files($this->path(), $this->db()->config()->extension);
    }

    /**
    * This will EMPTY the table
    * YOU CAN NOT UNDO THIS ACTION!
    *
    * It will keep the table directory alive
    * This will delete all documents within the table
    *
    * @return boolean
    */
    public function empty()
    {
        // TODO: empty table directory (but keep the table directory alive)
        return;
    }

    /**
    * This will DELETE the table
    * YOU CAN NOT UNDO THIS ACTION!
    *
    * This will delete the table directory
    * This will delete all documents within the table
    *
    * @return boolean
    */
    public function delete()
    {
        return $this->db()->fs()->rmdir('/'.$this->name());
    }

    /**
    * Query the table documents
    *
    * @return Filebase\Query
    */
    public function query()
    {
        return (new Query($this));
    }

    /**
    * Not exactly sure what this would be used for? 
    * 
    */
    public function genUniqFileId($item,$ext=".json")
    {
        $pre=0;
        while(true)
        {
            // you can use $this->db()->fs()->has() ?
            if(!file_exists($this->fullPath()."/".($item+$pre).$ext))
            {
                return ($item+$pre).$ext;
            }

            $pre++;
        }
    }

    /**
    * This will validate our table name
    * It will rename the table to the correct format
    * 
    */
    private function validateTableName($name)
    {
        // TODO: Validate and convert the name to the 
        // correct format "table_name" not "Table Name" 
        return $name;
    }

    /**
    * This will validate our table
    * It will create directory if does not exist
    * 
    * @return void
    */
    private function validateTable()
    {
        if (!$this->db->fs()->has($this->path)) {
            $this->db->fs()->mkdir($this->path);
        }
    }
}
