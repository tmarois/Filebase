<?php

namespace Filebase;

class DatabaseTest extends \PHPUnit\Framework\TestCase
{

    public function testDatabaseVersion()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

        $this->assertRegExp('/[0-9]+\.[0-9]+/', $db->version());
    }

}
