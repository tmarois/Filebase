<?php  namespace Filebase;


use Filebase\Filesystem\SavingException;

class badformat {

}


class DatabaseTest extends \PHPUnit\Framework\TestCase
{

    public function testVersion()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'read_only' => true
        ]);

        $this->assertRegExp('/[0-9]+\.[0-9]+\.[0-9]+/', $db->version());
    }


    public function testNotWritable()
    {
        $this->expectException(\Exception::class);

        if (!is_dir(__DIR__.'/databases/cantedit'))
        {
            mkdir(__DIR__.'/databases/cantedit');
        }

        chmod(__DIR__.'/databases/cantedit', 0444);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/cantedit'
        ]);

        chmod(__DIR__.'/databases/cantedit', 0777);
        rmdir(__DIR__.'/databases/cantedit');
    }


    public function testNotWritableButReadonly()
    {
        if (!is_dir(__DIR__.'/databases/cantedit'))
        {
            mkdir(__DIR__.'/databases/cantedit');
        }

        chmod(__DIR__.'/databases/cantedit', 0444);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/cantedit',
            'read_only' => true
        ]);

        $this->assertEquals(true, true);

        chmod(__DIR__.'/databases/cantedit', 0777);
        rmdir(__DIR__.'/databases/cantedit');
    }



    public function testDatabaseReadOnlyDelete()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);
        $db->get('test1')->set(['key'=>'value'])->save();

        $db2 = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'read_only' => true
        ]);

        $db2->get('test1')->delete();
    }




    public function testReadonlyBadFlush()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'read_only' => true
        ]);

        $db->flush(true);
    }


    public function testReadonlyBadTurncate()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'read_only' => true
        ]);

        $db->truncate();
    }



    public function testDatabaseBadSave()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'read_only' => true
        ]);

        $db->get('test1')->set(['key'=>'value'])->save();
    }



    public function testMissingFormatClass()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => ''
        ]);
    }


    public function testBadFormatClass()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => badformat::class
        ]);
    }


    public function testDatabaseFlushTrue()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test1')->set(['key'=>'value'])->save();
        $db->get('test2')->set(['key'=>'value'])->save();

        // true for all documents to be deleted.
        $this->assertEquals(true, $db->flush(true));
    }


    public function testDatabaseTruncate()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/test_delete'
        ]);

        $db->flush(true);

        $db->get('test1')->set(['key'=>'value'])->save();
        $db->get('test2')->set(['key'=>'value'])->save();

        $this->assertEquals(true, $db->truncate());

        $test = $db->get('test2');
        $this->assertEquals(null, $test->key);
    }


    public function testDatabaseFlushFalse()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test1')->set(['key'=>'value'])->save();
        $db->get('test2')->set(['key'=>'value'])->save();

        $db->flush();
    }


    public function testDatabaseFindAllSimple()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test1')->set(['key'=>'value'])->save();
        $db->get('test2')->set(['key'=>'value'])->save();

        $documents = $db->findAll(false);

        // should have 2 docs
        $this->assertEquals(2, count($documents));

        // check if these equal correctly
        $this->assertEquals('test1', $documents[0]);
        $this->assertEquals('test2', $documents[1]);

        $db->flush(true);
    }


    public function testDatabaseFindAllDataOnly()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $documents = $db->findAll(true,true);

        // should only have 1 doc
        $this->assertEquals(1, count($documents));
        $this->assertEquals(['key'=>'value'], $documents[0]);

        $db->flush(true);
    }

    public function testDatabaseSavingNotEncodableDocument()
    {
        $this->expectException(SavingException::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $doc = $db->get("testDatabaseSavingNotEncodableDocument");

        // insert invalid utf-8 characters
        $doc->testProp = "\xB1\x31";

        $doc->save();
    }
}
