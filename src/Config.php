<?php  namespace Flatfile;


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
