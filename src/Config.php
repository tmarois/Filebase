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
    public $format = Format\Json::class;


    /**
    * $cache
    * Caching for queries
    *
    */
    public $cache = false;


    /**
    * $cache_time
    * When should cache be cleared?
    *
    * default 30 minutes
    */
    public $cache_expires = 1800;


    /**
    * $validate
    *
    */
    public $validate = [];


    //--------------------------------------------------------------------


    /**
    * __construct
    *
    * This sets all the config variables (replacing its defaults)
    */
    public function __construct($config)
    {
        foreach ($config as $key => $value)
        {
            $this->{$key} = $value;
        }

        $this->validateFormatClass();
    }


    //--------------------------------------------------------------------


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
        $format_class = new $this->format;
        if (!$format_class instanceof Format\FormatInterface)
        {
            throw new \Exception('Format Class must be an instance of Filebase\Format\FormatInterface');
        }
    }


    //--------------------------------------------------------------------

}
