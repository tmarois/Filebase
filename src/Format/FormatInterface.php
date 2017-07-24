<?php namespace Flatfile\Format;

interface FormatInterface
{
    public static function getFileExtension();
    public static function encode($data);
    public static function decode($data);
}
