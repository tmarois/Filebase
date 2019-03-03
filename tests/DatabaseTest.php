<?php namespace Filebase\Test;

use Exception;
use Filebase\{Database,Table};
use Filebase\Config;

use Filebase\Test\TestCase;
use org\bovigo\vfs\vfsStream;
use Filebase\Support\Collection;


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
        $this->assertInternalType('array', $tables);
    }

    /**
     * @test
     */
    public function testMustReturnTablesNameInstanceOfCollection()
    {
        $tables = $this->db->tables();
        $this->assertCount(2,$tables);
        $this->assertInstanceOf(Collection::class,$tables);
    }
    /** @test */
    public function testMustReturnStandardName()
    {
        $tbl=$this->db->tableNameSanitizer('name','tbl_1');
        $this->assertEquals('tbl_1name',$tbl);
        
        $tbl=$this->db->config()->table_prefix='fafa_';
        $tbl=$this->db->tableNameSanitizer('name');
        $this->assertEquals('fafa_name',$tbl);

        $tbl=$this->db->tableNameSanitizer('tbl_name','tbl_');
        $this->assertEquals('tbl_name',$tbl);
    }
    /** @test */
    public function testMustCreateTableWithStandardName()
    {
        $tbl=$this->tmp_db->table('name');
        $this->assertFileExists($this->path."/tbl_name");
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
    /** @test */
    public function testMustReturnFiltredResultWithPattern()
    {
        $tbl=$this->db->filterTables(['one','two','tbl_three','tbl_four'],'tbl_');

        $this->assertCount(2,$tbl);
        $this->assertEquals(['tbl_three','tbl_four'],$tbl);     
        
        $this->db->config()->table_prefix="far_";
        $tbl=$this->db->filterTables(['one','far_two','tbl_three','tbl_four']);

        $this->assertCount(1,$tbl);
        $this->assertEquals(['far_two'],$tbl);   
    }
    /** @test */
    public function testMustCheckDatabaseHasTable()
    {
        $this->tmp_db->table('tbl_name');
        $actual=$this->tmp_db->hasTable('tbl_name');
        $this->assertTrue($actual);
        $actual=$this->tmp_db->hasTable('tbl_name_f');
        $this->assertFalse($actual);
    }
    
    /** @test */
    public function testMustRemoveAllDatabaseTables()
    {
        $this->tmp_db->table('tbl_name');
        $this->tmp_db->table('tbl_name2');
        $this->tmp_db->table('tbl_name3')->query()->create(['data'=>'content']);
        $this->tmp_db->empty();
        $this->assertFileExists($this->path);
        $this->assertCount(0,$this->tmp_db->tableList());
    }
    /**
     * @test
     */
    public function testMustDeleteDatabase()
    {
        $this->assertFileExists($this->path);
        $this->tmp_db->delete();
        $this->assertFileNotExists($this->path);

    }    
    /** @test */
    public function testMustCreateFilesystemInstanceWithObject()
    {
        $root=$this->root->url();
        $this->db = new Database([
            'path' => function() use ($root){
                return $root;
            },
        ]);
    }
    
}
