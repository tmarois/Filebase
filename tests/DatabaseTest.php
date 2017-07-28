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

        // true for all documents to be deleted.
        $this->assertEquals(true, $db->flush(true));
    }


    public function testDatabaseFindAllSimple()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_findall_database'
        ]);

        $db->get('test1')->set(['key'=>'value'])->save();
        $db->get('test2')->set(['key'=>'value'])->save();

        $documents = $db->findAll(false);

        $this->assertEquals(2, count($documents));
        $this->assertEquals('test1', $documents[0]);
        $this->assertEquals('test2', $documents[1]);
    }


    public function testDatabaseFindAllDataOnly()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_findall_database'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $documents = $db->findAll(true,true);

        $this->assertEquals(1, count($documents));
        $this->assertEquals(['key'=>'value'], $documents);
    }

}
