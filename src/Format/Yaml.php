<?php  namespace Filebase\Format;

use Symfony\Component\Yaml\Yaml as YamlParser;

class Yaml implements FormatInterface
{
    /**
     * @return string
     */
    public static function getFileExtension()
    {
        return 'yaml';
    }

    /**
     * @param array $data
     * @param bool $pretty
     * @return string
     * @throws FormatException
     */
    public static function encode($data = [], $pretty = true)
    {
        $encoded = YamlParser::dump((array)$data);
        return $encoded;
    }

    /**
     * @param $data
     * @return mixed
     * @throws FormatException
     */
    public static function decode($data)
    {
        $decoded = YamlParser::parse($data);
        return $decoded;
    }
}
