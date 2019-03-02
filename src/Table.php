<?php namespace Filebase;

use Filebase\Database;
use Filebase\Query;
use Filebase\Support\Filesystem;

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

    protected $fs;

    /**
    * Start up the table class
    *
    * @param string $name
    */
    public function __construct(Database $db, $name)
    {
        $this->db = $db;
        $this->path = DIRECTORY_SEPARATOR.$this->name;
        $this->fs=new Filesystem($this->fullPath());
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
        return $this->query()->find($name);
    }
    public function fs()
    {
        return $this->fs;
    }
    /**
    * Get a list of documents within our table
    * Returns an array of items
    *
    * @return array
    */
    public function getAllAsRaw()
    {
        return $this->fs()->files('.', $this->db()->config()->extension);
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
        $this->delete();
        $this->fs()->mkdir($this->name());
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
        // filesystem cant delete Herself so use database filesystem to remove table
        return $this->db()->fs()->rmdir($this->name());
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
            if(!$this->fs()->has(($item+$pre).$ext))
            {
                return ($item+$pre).$ext;
            }

            $pre++;
        }
    }
}
