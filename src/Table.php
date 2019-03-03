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

    /**
    * Filesystem class
    *
    * @var Filebase\Support\Filesystem
    */
    protected $fs;

    /**
    * Start up the table class
    *
    * @param string $name
    */
    public function __construct(Database $db, $name)
    {
        $this->db = $db;
        $this->name = $name;
        $this->path = DIRECTORY_SEPARATOR.$this->name;
        $this->fs = $db->fs();
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
        return $this->fs()->files($this->name(), $this->db()->config()->extension);
    }

    /**
    * This will EMPTY the table
    * YOU CAN NOT UNDO THIS ACTION!
    *
    * It will keep the table directory alive
    * This will delete all documents within the table
    *
    * @return void
    */
    public function empty()
    {
        $this->delete();
        $this->fs()->mkdir($this->name());
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
        // table fileSystem cant delete Herself so use database filesystem to remove table
        return $this->fs()->rmdir($this->name());
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
    * Check if this table has a specific document
    *
    * @return boolean
    */
    public function has($name)
    {
        // might need a better check on this because "findOrFail"
        // returns document data which increases memory usage
        $doc = $this->query()->findOrFail($name);
        return ($doc) ? true : false;
    }

    /**
    * Not exactly sure what this would be used for? 
    * 
    */
    public function genUniqFileId($item=0,$ext=null)
    {
        $ext=$ext==null ? $this->db->config()->extension : trim($ext,'.');
        $pre=0;
        while(true)
        {
            if(!$this->fs()->has($this->name().'/'.($item+$pre).'.'.$ext))
                return ($item+$pre).'.'.$ext;
            $pre++;
        }
    }

   /**
    * Magic method to give us access to query methods on table class
    *
    */
    public function __call($method,$args)
    {
        if(method_exists($this,$method)) {
            return $this->$method(...$args);
        }

        if(method_exists(Query::class,$method)) {
            return $this->query()->$method(...$args);
        }

        throw new \BadMethodCallException("method {$method} not found on 'Database::class' and 'Query::class'");
    }
}
