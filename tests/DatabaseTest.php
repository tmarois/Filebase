<?php namespace Filebase\Test;

use Exception;
use Filebase\{Database,Table};
use Filebase\Config;

use Filebase\Test\TestCase;
use org\bovigo\vfs\vfsStream;


class DatabaseTest extends TestCase
{

    public $root;
    public $db;

    /**
    * setUp()
    *
    *
    */
    protected function setUp():void
    {
        parent::setUp();
        
        $this->root = vfsStream::setup('baseFolderName',null,['tbl_one'=>[],'tbl_two'=>[]]);

        $this->db = new Database([
            'path' => $this->root->url()
        ]);
    }

    /**
    * testDatabaseConfig()
    *
    * TEST:
    * (1) Check that our config is properly setup
    *
    */
    public function testDatabaseConfig()
    {
        $db = new Database([
            'path' => __DIR__.'/database'
        ]);

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
        $path1 = __DIR__.'/database';
        $path2 = __DIR__.'/database1';

        $db = new Database([
            'path' => $path1,
            'readonly' => true
        ]);

        // (1) check we're getting the config variables

        $this->assertEquals($path1, $db->config()->path);
        $this->assertEquals(true, $db->config()->readonly);

        // reset the config
        $db->setConfig(['path'=>$path2]);

        // (2) setting new database configs

        // check we replaced the variable
        $this->assertEquals($path2, $db->config()->path);
        // check the other variable reset to default
        $this->assertEquals(false, $db->config()->readonly);
    }

    /**
     * @test
     */
    public function testDatabaseTableList()
    {
        $tables = $this->db->tableList();
        $this->assertCount(2,$tables);
    }

    /**
     * @test
     */
    public function testMustReturnTablesName()
    {
        $tables = $this->db->tables();
        $this->assertCount(2,$tables);
    }

    /**
     * @test
     */
    public function testMustCreateTableDirIfNotExist()
    {
        $tables = $this->db->table('tbl_new');
        $this->assertTrue($this->root->hasChild('tbl_new'));
    }

    /**
     * @test 
     */
    public function testMustDeleteTable()
    {
        $this->db->table('tbl_new');
        $this->db->table('tbl_new')->delete();
        $this->assertFalse($this->root->hasChild('tbl_new'));
    }

    /**
     * @test
     */
    public function testMustReturnInstanceOfTable()
    {
        $tbl=$this->db->table('tbl_new');
        $tb2=$this->db->table('tbl_one');
        $this->assertInstanceOf(Table::class,$tbl);
        $this->assertInstanceOf(Table::class,$tb2);
    }

}
