<?php

namespace Filebase;

use Exception;

class DatabaseTest extends \PHPUnit\Framework\TestCase
{

    public function testDatabaseVersion()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $this->assertRegExp('/[0-9]+\.[0-9]+/', $db->version());
    }


    public function testDatabaseEmpty()
    {
        $db = new Database([
            'path' => __DIR__.'/database/test-empty'
        ]);

        for ($x = 1; $x <= 10; $x++)
        {
            $user = $db->document(uniqid());
            $user->name = 'John';
            $user->contact['email'] = 'john@john.com';
            $user->save();
        }

        $before = $db->count();

        $db->empty();

        $this->assertEquals(10, $before);
        $this->assertEquals(0, $db->count());
    }


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


    public function testNotWritableButReadonly()
    {
        if (!is_dir(__DIR__.'/database/cantedit'))
        {
            mkdir(__DIR__.'/database/cantedit');
        }
        chmod(__DIR__.'/database/cantedit', 0444);

        $db = new \Filebase\Database([
            'path' => __DIR__.'/database/cantedit',
            'readOnly' => true
        ]);

        $this->assertEquals(true, true);

        chmod(__DIR__.'/database/cantedit', 0777);
        rmdir(__DIR__.'/database/cantedit');
    }


    public function testDatabaseReadOnlyDelete()
    {
        $this->expectException(Exception::class);

        $db = new \Filebase\Database([
            'path' => __DIR__.'/database'
        ]);

        $doc = $db->document('test1');
        $doc->name = 'Test';
        $doc->save();

        $db2 = new \Filebase\Database([
            'path' => __DIR__.'/database',
            'readOnly' => true
        ]);

        $doc = $db2->document('test1');
        $doc->delete();
    }


    public function testDatabaseReadOnlyEmpty()
    {
        $this->expectException(Exception::class);

        $db2 = new \Filebase\Database([
            'path' => __DIR__.'/database',
            'readOnly' => true
        ]);

        $db2->empty();
    }

}
