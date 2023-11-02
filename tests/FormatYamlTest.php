<?php  namespace Filebase;

use Filebase\Format\DecodingException;
use Filebase\Format\EncodingException;
use Filebase\Format\Yaml;

class FormatYamlTest extends \PHPUnit\Framework\TestCase
{
    public function testFormatYamlEnsureDataIntegrity()
    {
        $data = [
            'name' => 'timothy-m_arois',
            'email' => 'email@email.com'
        ];

        $Yaml = Yaml::encode($data, false);
        $testData = Yaml::decode($Yaml);

        $this->assertEquals($data, $testData);
    }

}