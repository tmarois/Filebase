<?php  namespace Filebase;


class Config
{

    /**
    * __construct
    *
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
