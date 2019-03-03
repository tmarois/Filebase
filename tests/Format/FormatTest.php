<?php namespace Filebase\Test;

use Filebase\Format\FormatInterface;
use Filebase\Format\Json;

use Filebase\Test\TestCase;

class FormatTest extends TestCase
{
    /**
    * testFormatJson()
    *
    * TEST:
    * (1) check the interface is on FormatInterface
    *
    */
    public function testFormatJson()
    {
        $this->assertInstanceOf(FormatInterface::class,(new Json)); 
    }

    /**
    * testFormatJsonEncode()
    *
    * TEST:
    * (1) Check that we encoded correctly
    *
    */
    public function testFormatJsonEncode()
    {
        $data = ['test1','test2'];

        $jsonFormatter = new Json();
        $encoded = $jsonFormatter->encode($data);

        $this->assertEquals('["test1","test2"]', $encoded); 
    }

    /**
    * testFormatJsonDecode()
    *
    * TEST:
    * (1) Check that we decoded correctly
    *
    */
    public function testFormatJsonDecode()
    {
        $data = ['test1','test2'];

        $jsonFormatter = new Json();
        $encoded = $jsonFormatter->encode($data);
        $decoded = $jsonFormatter->decode($encoded);

        $this->assertEquals($data, $decoded); 
    }

}
