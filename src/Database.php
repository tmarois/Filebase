<?php  namespace Filebase;

use Exception;
use Filebase\Config;
use Filebase\Table;
use Filebase\Support\Filesystem;

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
     * The database filesystem
     *
     * @see Filebase\Support\Filesystem
     */
    protected $filesystem;

    /**
     * Start up the database class
     *
     * @param array $config 
     */
    public function __construct(array $config = [])
    {
        // set up our configuration class
        $this->setConfig($config);
        $this->filesystem = new Filesystem($this->config->path);
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
    * This uses a fresh config and update Filesystem (path)
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
     * 
     * @return Filebase\Table
     */
    public function table($name)
    {
        if (!$this->hasTable($name)) {
            $this->fs()->mkdir($this->tableNameSanitizer($name));
        }
        return (new Table($this, $this->tableNameSanitizer($name)));
    }
    public function tableNameSanitizer($name,$table_prefix=null)
    {
        $table_prefix=$table_prefix==null ? $this->config
                                ->table_prefix : $table_prefix;

        return preg_match("/^".$table_prefix."/is",$name)
                                 ? $name : $table_prefix.$name;
    }
    /**
    * Get all of the tables within our database
    * Returns a Collection object of Tables
    *
    * @return array
    */
    public function tables()
    {
        return new Collection(array_map(function ($table) {
            return $this->table($table);
        }, $this->tableList()));
    }

    /**
    * Get a list of tables within our database
    * Returns an array of table names
    *
    * @return array
    */
    public function tableList()
    {
        return $this->filterTables($this->fs()->folders());
    }

    public function filterTables(array $args,$table_prefix=null)
    {
        $table_prefix=$table_prefix==null ? $this->config->table_prefix : $table_prefix;
        $pattern='/^'.$table_prefix.'/is';
        
        return array_values(array_filter($args,function($item) use ($pattern){
            return preg_match($pattern,$item);   
        }));
    }
    /**
    * Ability to use the filesystem outside classes
    *
    * @return Filebase\Support\Filesystem
    */
    public function fs()
    {
        return $this->filesystem;
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
        // TODO: delete all table directores
        // keep the database directory alive
        foreach ($this->tableList() as $key => $value) {
            $this->fs()->rmdir($value);
        }
        return true;
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
        $path=explode('/',trim($this->config()->path,'/'));
        $fs=new Filesystem($this->config()->path."../");
        return $fs->rmdir(end($path));
    }
    public function hasTable($name)
    {
        return in_array($name,$this->tableList());
    }
   
}
