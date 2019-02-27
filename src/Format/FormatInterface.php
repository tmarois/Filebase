<?php namespace Filebase\Format;

/**
 * The format interface
 * All format classes must extend this
 */
interface FormatInterface
{
    /**
     * the encoding method
     * 
     * @param array $data
     */
    public static function encode($data, $prettyPrint);

    /**
     * the decoding method
     * 
     * @param string $data
     */
    public static function decode($data);
}
