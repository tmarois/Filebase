<?php  namespace Filebase;

use Exception;
use Base\Support\Filesystem;

class Database
{

    /**
    * VERSION
    *
    * Stores the version of Filebase
    * use $db->getVersion()
    *
    * @return string
    */
    const VERSION = '2.0';


    /**
    * $config
    *
    * Stores all the configuration object settings
    *
    * @see Filebase\Config
    */
    protected $config;


    /**
    * __construct
    *
    */
    public function __construct(array $config = [])
    {
        $this->config = new Config($config);

        if ($this->config->readOnly === false)
        {
            $this->createDirectory();
        }
    }


    /**
    * createDirectory()
    *
    * Create the database directory if doesnt exists,
    * And check that the directory is writeable
    *
    * @return void
    */
    protected function createDirectory()
    {
        if (!Filesystem::isDirectory($this->config->path))
        {
            if (!@Filesystem::makeDirectory($this->config->path, 0777, true))
            {
                throw new Exception(sprintf('`%s` doesn\'t exist and can\'t be created.', $this->config->path));
            }
        }
        else if (!Filesystem::isWritable($this->config->path))
        {
            throw new Exception(sprintf('`%s` is not writable.', $this->config->path));
        }
    }


    /**
    * version
    *
    * gets the Filebase version
    *
    * @return VERSION
    */
    public function version()
    {
        return self::VERSION;
    }


    /**
    * config
    *
    * @return Filebase\Config
    */
    public function config()
    {
        return $this->config;
    }


    /**
    * document()
    *
    * @param string $name
    * @return Filebase\Document
    */
    public function document($name, $isCollection = true)
    {
        return (new Document($this, $name, $isCollection));
    }


    /**
    * backup
    *
    * @param string $location (optional)
    * @return Filebase\Backup
    */
    public function backup($path = null)
    {
        $path = ($path) ?? $this->config->backupPath;

        return (new Backup($this, $path));
    }


    /**
    * query
    *
    *
    */
    public function query()
    {
        // return (new Query($this));
    }


    /**
    * count()
    *
    * Counts all the database items (files in directory)
    *
    * @return int
    */
    public function count()
    {
        return count(Filesystem::getAll($this->config->path,$this->config->ext));
    }


    /**
    * truncate
    *
    * Empties entire database directory files
    *
    * @return bool
    */
    public function truncate()
    {
        if ($this->config->readOnly === true)
        {
            throw new Exception("Filebase: This database is set to be read-only. No modifications can be made.");
        }

        return Filesystem::empty($this->config->path);
    }


    /**
    * empty
    *
    * Alias for truncate()
    *
    * @see truncate
    * @return void
    */
    public function empty()
    {
        return $this->truncate();
    }

}
