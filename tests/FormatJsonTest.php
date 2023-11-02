<?php  namespace Filebase;

use Filebase\Format\DecodingException;
use Filebase\Format\EncodingException;
use Filebase\Format\Json;

class FormatJsonTest extends \PHPUnit\Framework\TestCase
{
    public function testFormatJsonEnsureDataIntegrity()
    {
        $data = [
            'name' => 'timothy-m_arois',
            'email' => 'email@email.com'
        ];

        $json = Json::encode($data, false);
        $testData = Json::decode($json);

        $this->assertEquals($data, $testData);
    }

    public function testFormatJsonEncodeThrowsExceptionIfNotEncodable()
    {
        $this->expectException(EncodingException::class);

        $data = [
            'invalid' => chr(193)
        ];

        Json::encode($data);
    }

    public function testFormatJsonDecodeThrowsExceptionOnNotValidJson()
    {
        $this->expectException(DecodingException::class);

        $json = '{ invalid: "json"';

        Json::decode($json);
    }
}