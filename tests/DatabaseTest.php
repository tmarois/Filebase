<?php  namespace Filebase;


class DatabaseTest extends \PHPUnit\Framework\TestCase
{

    public function testDatabaseFlushTrue()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('test1')->set(['key'=>'value'])->save();
        $db->get('test2')->set(['key'=>'value'])->save();

        $this->assertEquals(0, $db->flush(true));
    }

}
