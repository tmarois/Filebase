<?php namespace Filebase;

use Exception;
use Filebase\Database;
use Base\Support\Filesystem;

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
            'path' => __DIR__.'/database',
            'readOnly' => true,
            'errors' => false
        ]);

        $this->assertRegExp('/[0-9]+\.[0-9]+/', $db->version());
    }


    /**
    * testDatabaseConfig()
    *
    * TEST:
    * (1) Test getting the database config values
    * (2) Check that we can get correct read-only and allowed errors values
    *
    */
    public function testDatabaseConfig()
    {
        $db = new Database([
            'path' => __DIR__.'/database',
            'readOnly' => true,
            'errors' => false
        ]);

        $this->assertEquals('db', $db->config()->ext);
        $this->assertEquals(null, $db->config()->doesnotexist);
        $this->assertEquals(false, $db->config()->errors);
        $this->assertEquals(true, $db->config()->readOnly);
        $this->assertEquals(true, $db->isReadOnly());
        $this->assertEquals(false, $db->allowErrors());
    }


    /**
    * testDatabaseWithErrors()
    *
    * TEST:
    * (1) Test Fatal error is thrown because we cant create the db directory
    *     on read-only mode
    *
    */
    public function testDatabaseWithErrors()
    {
        $this->expectException(Exception::class);

        $db = new Database([
            'path' => __DIR__.'/database',
            'readOnly' => true,
            'errors' => true
        ]);
    }


    /**
    * testDatabaseCreateDirectory()
    *
    * TEST:
    * (1) Test to see that the database auto-created directory
    *
    */
    public function testDatabaseCreateDirectory()
    {
        $makeDir = __DIR__.'/make-database';

        Filesystem::deleteDirectory($makeDir);

        $db = new Database([
            'path' => $makeDir
        ]);

        $this->assertEquals(true, Filesystem::isDirectory($makeDir));

        Filesystem::deleteDirectory($makeDir);
    }


    /**
    * testDatabaseNotCreatedDirectory()
    *
    * TEST:
    * (1) Test to see that the database do not auto-create directory
    *
    */
    public function testDatabaseNotCreatedDirectory()
    {
        $makeDir = __DIR__.'/make-database';

        Filesystem::deleteDirectory($makeDir);

        $db = new Database([
            'path' => $makeDir,
            'readOnly' => true,
            'errors' => false
        ]);

        $this->assertEquals(false, Filesystem::isDirectory($makeDir));

        Filesystem::deleteDirectory($makeDir);
    }


    /**
    * testNotWritable()
    *
    * TEST:
    * (1) If a directory can not be modified - throw exception
    *
    */
    public function testNotWritable()
    {
        $this->expectException(Exception::class);

        $badDir = __DIR__.'/bad-database';

        Filesystem::makeDirectory($badDir, 0444);

        $db = new Database([
            'path' => $badDir
        ]);
    }


    /**
    * testNotWritableButReadonly()
    *
    * TEST:
    * If a directory can not be modified,
    * and Database is READ-ONLY, should be allowed to proceed
    *
    */
    public function testNotWritableButReadonly()
    {
        $badDir = __DIR__.'/bad-database';

        Filesystem::makeDirectory($badDir, 0444);

        $db = new Database([
            'path' => $badDir,
            'readOnly' => true
        ]);

        $this->assertEquals(true, true);

        Filesystem::deleteDirectory($badDir);
    }

}
