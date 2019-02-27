<?php namespace Filebase;

use Exception;
use Filebase\Database;
use Filebase\Config;

class DatabaseTest extends \PHPUnit\Framework\TestCase
{

    /**
    * testDatabaseConfig()
    *
    * TEST:
    * (1) Check that our config is properly setup
    *
    */
    public function testDatabaseConfig()
    {
        $db = new Database();
        $this->assertInstanceOf(Config::class,$db->config()); 
    }
    
    /**
    * testDatabaseConfigSetup()
    *
    * TEST:
    * (1) Test getting the database config values
    * (2) Test setting a new database config 
    *
    */
    public function testDatabaseConfigSetup()
    {
        $path = __DIR__.'/database';

        $db = new Database([
            'path' => $path,
            'readonly' => true
        ]);

        // (1) check we're getting the config variables

        $this->assertEquals($path, $db->config()->path);
        $this->assertEquals(true, $db->config()->readonly);

        // reset the config
        $db->setConfig(['path'=>'/my/new/path']);

        // (2) setting new database configs

        // check we replaced the variable
        $this->assertEquals('/my/new/path', $db->config()->path);
        // check the other variable reset to default
        $this->assertEquals(false, $db->config()->readonly);
    }

}
