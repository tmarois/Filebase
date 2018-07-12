<?php namespace Filebase;

use Exception;

class DatabaseTest extends \PHPUnit\Framework\TestCase
{

    /**
    * testDatabaseVersion()
    *
    * TEST:
    * Get the Filebase VERSION
    *
    */
    public function testDatabaseVersion()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $this->assertRegExp('/[0-9]+\.[0-9]+/', $db->version());
    }


    /**
    * testDatabaseConfig()
    *
    * TEST:
    * Test getting a database item
    *
    */
    public function testDatabaseConfig()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $this->assertEquals('db', $db->config()->ext);
        $this->assertEquals(null, $db->config()->doesnotexist);
    }


    /**
    * testDatabaseCountEmpty()
    *
    * TEST:
    * (1) Test database COUNT() is working
    * (2) EMPTY the entire database
    *
    */
    public function testDatabaseCountEmpty()
    {
        $db = new Database([
            'path' => __DIR__.'/database/test-empty'
        ]);

        for ($x = 1; $x <= 10; $x++)
        {
            $user = $db->document(uniqid());
            $user->name = 'John';
            $user->save();
        }

        $before = $db->count();

        $db->empty();

        $this->assertEquals(10, $before);
        $this->assertEquals(0, $db->count());
    }


    /**
    * testNotWritable()
    *
    * TEST:
    * If a directory can not be modified
    *
    */
    public function testNotWritable()
    {
        $this->expectException(Exception::class);

        if (!is_dir(__DIR__.'/database/cantedit'))
        {
            mkdir(__DIR__.'/database/cantedit');
        }

        chmod(__DIR__.'/database/cantedit', 0444);

        $db = new Database([
            'path' => __DIR__.'/database/cantedit'
        ]);
    }


    /**
    * testNotWritableButReadonly()
    *
    * TEST:
    * If a directory can not be modified,
    * and Database is READ-ONLY
    *
    */
    public function testNotWritableButReadonly()
    {
        if (!is_dir(__DIR__.'/database/cantedit'))
        {
            mkdir(__DIR__.'/database/cantedit');
        }
        chmod(__DIR__.'/database/cantedit', 0444);

        $db = new Database([
            'path' => __DIR__.'/database/cantedit',
            'readOnly' => true
        ]);

        $this->assertEquals(true, true);

        chmod(__DIR__.'/database/cantedit', 0777);
        rmdir(__DIR__.'/database/cantedit');
    }


    /**
    * testDatabaseReadOnlyDelete()
    *
    * TEST:
    * Test delete if database is READ-ONLY
    *
    */
    public function testDatabaseReadOnlyDelete()
    {
        $this->expectException(Exception::class);

        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('test1');
        $doc->name = 'Test';
        $doc->save();

        $db2 = new Database([
            'path' => __DIR__.'/database',
            'readOnly' => true
        ]);

        $doc = $db2->document('test1');
        $doc->delete();
    }


    /**
    * testDatabaseReadOnlyEmpty()
    *
    * TEST:
    * Test DB EMPTY if database is READ-ONLY
    *
    */
    public function testDatabaseReadOnlyEmpty()
    {
        $this->expectException(Exception::class);

        $db2 = new Database([
            'path' => __DIR__.'/database',
            'readOnly' => true
        ]);

        $db2->empty();
    }

}
