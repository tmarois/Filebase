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
    * Create the database directory if doesnt exists
    *
    * @return void
    */
    protected function createDirectory()
    {
        // Check directory and create it if it doesn't exist
        /*if (!is_dir($this->config->dir))
        {
            if (!@mkdir($this->config->dir, 0777, true))
            {
                throw new Exception(sprintf('`%s` doesn\'t exist and can\'t be created.', $this->config->dir));
            }
        }
        else if (!is_writable($this->config->dir))
        {
            throw new Exception(sprintf('`%s` is not writable.', $this->config->dir));
        }*/
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
    * document()
    *
    * @param string $name
    * @return Filebase\Document
    */
    public function document($name)
    {
        return (new Document($this, $name));;
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

        return (new Backup($path, $this));
    }


    /**
    * query
    *
    *
    */
    public function query()
    {
        return (new Query($this));
    }


    /**
    * truncate
    *
    *
    * @return void
    */
    public function truncate()
    {
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
