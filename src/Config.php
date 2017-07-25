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
    }


    //--------------------------------------------------------------------

}
