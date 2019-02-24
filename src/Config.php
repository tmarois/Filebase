<?php  namespace Filebase;


class Config
{

    /**
    * $dir
    * Database Directory
    * Where to store information
    */
    public $dir = __DIR__;

    /**
    * $format
    * Format Class
    * Must implement Format\FormatInterface
    */
    public $format = \Filebase\Format\Json::class;

    /**
    * $cache
    * Caching for queries
    *
    * default true
    */
    public $cache = true;

    /**
    * $cache_time
    * When should cache be cleared?
    *
    * default (1800 seconds) 30 minutes
    */
    public $cache_expires = 1800;

    /**
    * $safe_filename
    * (if true) Be sure to automatically change the file name if it does not fit validation
    * (if false) File names that are not valid will thrown an error.
    *
    * default true
    */
    public $safe_filename = true;

    /**
    * $read_only
    * (if true) We will not attempt to create the database directory or allow the user to create anything
    * (if false) Functions as normal
    *
    * default false
    */
    public $read_only = false;

    /**
    * $backupLocation
    * The location to store backups
    *
    * default current location
    */
    public $backupLocation = '';

    /**
    * $pretty
    *
    * if true, saves the data as human readable
    * Otherwise, its difficult to understand.
    *
    * default true
    */
    public $pretty = true;

    /**
    * $validate
    *
    */
    public $validate = [];

    /**
    * __construct
    *
    * This sets all the config variables (replacing its defaults)
    */
    public function __construct($config)
    {
        // let's define all our config variables
        foreach ($config as $key => $value)
        {
            $this->{$key} = $value;
        }

        // if "backupLocation" is not set, let's set one automatically
        if (!isset($config['backupLocation']))
        {
            $this->backupLocation = $this->dir.'/backups';
        }

        $this->validateFormatClass();
    }

    /**
    * format
    *
    * kind of a quick fix since we are using static methods,
    * currently need to instantiate teh class to check instanceof why??
    *
    * Checks the format of the database being accessed
    */
    protected function validateFormatClass()
    {
        if (!class_exists($this->format))
        {
            throw new \Exception('Filebase Error: Missing format class in config.');
        }

        // instantiate the format class
        $format_class = new $this->format;

        // check now if that class is part of our interface
        if (!$format_class instanceof \Filebase\Format\FormatInterface)
        {
            throw new \Exception('Filebase Error: Format Class must be an instance of Filebase\Format\FormatInterface');
        }
    }
}
