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
    public function test_Call_Queryclass_methods_on_database_without_query_method()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/saved',
            'cache' => true
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name  = 'John';
            $user->email = 'john@example.com';
    		$user->save();
    	}

        $results = $db->where('name','=','John')->andWhere('email','==','john@example.com')->resultDocuments();
        $result_from_cache = $db->where('name','=','John')->andWhere('email','==','john@example.com')->resultDocuments();

        $this->assertEquals(10, count($results));
        $this->assertEquals(true, ($result_from_cache[0]->isCache()));

        $id = $result_from_cache[0]->getId();
        $id2 = $result_from_cache[1]->getId();

        // Change the name
        $result_from_cache[0]->name = 'Tim';
        $result_from_cache[0]->save();

        $results = $db
    	 	->where('name','=','John')
            ->andWhere('email','==','john@example.com')
    		->resultDocuments();

        $this->assertEquals($id2, $results[0]->getId());
        $this->assertEquals('John', $results[0]->name);

        $db->flush(true);
    }
    public function test_must_return_exception_on_non_exist_method()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/saved',
            'cache' => true
        ]);

        $this->expectException(\BadMethodCallException::class);
        $results = $db->none('name','=','John')->andWhere('email','==','john@example.com')->resultDocuments();
    }
    /**
     * based on issue #41
     * results() returns document instead of array #41
     */
    public function test_must_return_array_on_select_an_culomn_from_cache()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/saved',
            'cache' => true
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name  = 'John';
            $user->email = 'john@example.com';
    		$user->save();
    	}

        $db->where('name','=','John')->andWhere('email','==','john@example.com')->select('email')->results();
        $result_from_cache = $db->where('name','=','John')->andWhere('email','==','john@example.com')->select('email')->results();

        $this->assertCount(10,$result_from_cache);
        $this->assertEquals(['email'=>'john@example.com'],$result_from_cache[0]);
        $this->assertInternalType('array', $result_from_cache[0]);
        $this->assertInternalType('string', $result_from_cache[0]['email']);
        $db->flush(true);
    }
}
