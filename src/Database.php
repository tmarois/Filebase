<?php  namespace Filebase;

use Exception;
use Filebase\Config;
use Filebase\Table;

/**
 * The database class
 * 
 * This class access the core
 * package functionality
 * 
 */
class Database
{

   /**
    * Stores all the configuration object settings
    *
    * @see Filebase\Config
    */
    protected $config;

   /**
    * Start up the database class
    *
    * @param array $config
    */
    public function __construct(array $config = [])
    {
        // set up our configuration class
        $this->config = $this->setConfig($config);
    }

   /**
    * Public access to the config class and its methods
    *
    * @return Filebase\Config
    */
    public function config()
    {
        return $this->config;
    }

    /**
    * Setting the configuration for our database
    * This uses a fresh config
    *
    * @param array $config
    * @return Filebase\Config
    */
    public function setConfig(array $config = [])
    {
        $this->config = (new Config($config));

        return $this->config;
    }

   /**
    * Public access to the config class and its methods
    *
    * @param string $name
    * @return Filebase\Table
    */
    public function table($name)
    {
        return (new Table($this, $name));
    }

    /**
    * Get all of the tables within our database
    * Returns a Collection object of Tables
    *
    * @return Collection
    */
    public function tables()
    {
        return;
    }

    /**
    * This will EMPTY the entire database
    * YOU CAN NOT UNDO THIS ACTION!
    *
    * It will keep the database directory alive
    * This will delete all tables (directories)
    * This will delete all documents (items)
    *
    * @return boolean
    */
    public function empty()
    {
        return;
    }

    /**
    * This will DELETE the entire database
    * YOU CAN NOT UNDO THIS ACTION!
    *
    * This will delete the root database directory
    * This will delete all tables (directories)
    * This will delete all documents (items)
    *
    * @return boolean
    */
    public function delete()
    {
        return;
    }

}
