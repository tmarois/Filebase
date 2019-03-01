<?php namespace Filebase\Format;

use Filebase\Format\FormatInterface;

/**
 * The JSON format class
 * Used as the default database format
 * 
 */
class Json implements FormatInterface
{

   /**
    * Encoding the data into JSON
    *
    * @param array data
    * @return string json_encode
    */
    public static function encode($data = [], $prettyPrint = false)
    {
        $p = ($prettyPrint===true) ? (JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) : (1);

        return json_encode($data, $p);
    }

    /**
    * Decoding the data into JSON
    *
    * @param array data
    * @return string json_decode
    */
    public static function decode($data)
    {
        return json_decode($data);
        // return json_decode($data, 1);
    }

}
