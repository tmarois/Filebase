<?php  namespace Filebase;

use Filebase\Format\Json;
use Filebase\Format\FormatInterface;
use Exception;

class Config
{

    /**
    * $path
    *
    * @var string
    */
    protected $path = __DIR__.'/database';


    /**
    * $backupPath
    *
    * @var string
    */
    protected $backupPath = __DIR__.'/backups';


    /**
    * $format
    * Format Class
    * Must implement Format\FormatInterface
    */
    protected $format = Json::class;


    /**
    * $read_only
    * (if true) We will not attempt to create the database directory or allow the user to create anything
    * (if false) Functions as normal
    *
    * default false
    */
    protected $readOnly = false;


    /**
    * $pretty
    *
    * if true, saves the data as human readable
    * Otherwise, its difficult to understand.
    *
    * default true
    */
    protected $prettyFormat = true;


    /**
    * $fileExtension
    *
    * default file extension
    *
    * @var string
    */
    protected $ext = 'db';


    /**
    * __construct
    *
    * This sets all the config variables (replacing its defaults)
    */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value)
        {
            if (isset($this->$key))
            {
                $this->$key = $value;
            }
        }

        $this->validateFormatClass();
    }


    /**
    * get property
    *
    * @param string $name
    * @return mixed
    */
    public function __get($name)
    {
        if (isset($this->$name))
        {
            return $this->$name;
        }

        return null;
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
            throw new Exception('Filebase: Missing format class in config.');
        }

        // instantiate the format class
        $formatClass = (new $this->format);

        // check now if that class is part of our interface
        if (!$formatClass instanceof FormatInterface)
        {
            throw new Exception('Filebase: Format Class must be an instance of Filebase\Format\FormatInterface');
        }
    }

}
