<?php  namespace Filebase;


class DocumentTest extends \PHPUnit\Framework\TestCase
{

    public function testGetDocumentValue()
    {
        $db = new \Filebase\Database();
        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test');

        $this->assertEquals('value', $test->value);

    }

}
