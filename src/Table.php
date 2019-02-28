<?php namespace Filebase;

use Filebase\Database;
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

        $this->name = $name;
        $this->path = DIRECTORY_SEPARATOR.$this->name;

        // if this directory (table) does not exist
        // lets automatically create it
        $this->validateTable();
    }
    private function validateTable()
    {
        if (!$this->db->fs()->has($this->path)) {
            $this->db->fs()->mkdir($this->path);
        }
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
    * Get our table path (directory location)
    *
    * @return string
    */
    public function path()
    {
        return $this->path;
    }

    public function fullPath($path=null)
    {
        return $this->db()->config()->path
                    .$this->path() ;
    }

    /**
    * Get a single document within this table
    *
    * @param string $name
    * @return Filebase\Document
    */
    public function get($name)
    {
        return (new Document($this, $name));
    }

    // /**
    // * Get all of the tables within our database
    // * Returns a Collection object of Tables
    // *
    // * @return array
    // */
    // public function list()
    // {
    //     return array_map(function($document) {
    //         return $this->get($document['basename']);
    //     }, $this->getList());
    // }

    /**
    * Get a list of documents within our table
    * Returns an array of items
    *
    * @return array
    */
    public function getAll()
    {
        return $this->db()->fs()->files($this->path());
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
    
    public function query()
    {
        return new Query($this);
    }

    public function genUniqFileId($item,$ext=".json")
    {
        $pre=0;
        while(true)
        {
            if(!file_exists($this->fullPath()."/".($item+$pre).$ext))
            {
                return ($item+$pre).$ext;
            }
            $pre++;
        }
    }
}
