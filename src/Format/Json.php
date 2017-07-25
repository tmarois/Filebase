<?php  namespace Filebase\Format;


class Json implements FormatInterface
{

    /**
    * getFileExtension
    *
    */
    public static function getFileExtension()
    {
        return 'json';
    }


    //--------------------------------------------------------------------


    /**
    * encode
    *
    */
    public static function encode($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }


    //--------------------------------------------------------------------


    /**
    * decode
    *
    */
    public static function decode($data)
    {
        return json_decode($data,1);
    }


    //--------------------------------------------------------------------


}
