<?php 
namespace Filebase\Test;

use Filebase\{Database,Table,Query};
use Filebase\{Document,Collection};

use Filebase\Test\TestCase;
use org\bovigo\vfs\vfsStream;

class QueryTest extends TestCase
{
    public $db;
    public $query;

    protected function setUp():void
    {
        parent::setUp();

        $this->db=new Database([
            'path' => $this->path
        ]);
        
        $this->query=new Query(new Table($this->db,'tbl_one'));
    }

    /**
     * @test
     */
    public function testMustReturnTable()
    {
        $tbl = $this->query->table();
        $this->assertInstanceOf(Table::class,$tbl);
    }
    
    /**
     * @test
     */
    public function testMustReturnInstanceOfDatabase()
    {
        $db = $this->query->db();
        $this->assertInstanceOf(Database::class,$db);
    }

    /**
     * @test
     */
    public function testMustInsertNewRecordOnTable()
    {

        // check create
        $this->query->create(['name'=>'John','last_name'=>'Doe']);
        $this->assertFileExists($this->path."/tbl_one/0.json");

        $this->query->create(['name'=>'John','last_name'=>'Doe']);
        $this->assertFileExists($this->path."/tbl_one/1.json");

        // check file have data
        $this->assertJsonStringEqualsJsonFile(
            $this->path."/tbl_one/1.json", json_encode(['name'=>'John','last_name'=>'Doe'])
        );
    }
    
    /** @test */
    public function testMustReturnDbRecordWithId()
    {
        $this->query->create(['name'=>'John','last_name'=>'Doe']);
        $record=$this->query->find(0)->toArray();

        $this->assertEquals(['name'=>'John','last_name'=>'Doe'],$record);
    }

    /** @test */
    public function testMustReturnInstanceOfDocument()
    {
        $this->query->create(['name'=>'John','last_name'=>'Doe']);
        $record=$this->query->find(0);

        $this->assertInstanceOf(Document::class,$record);
    }

    /** @test */
    public function testMustRemoveRecord()
    {
        $this->query->create(['name'=>'John','last_name'=>'Doe']);
        $record=$this->query->find(0)->delete();

        $this->assertFileNotExists($this->path."/tbl_one/0.json");
    }
    /** @test */
    public function testMustReturnEmptyDocumnetInstanceOnNoneExistItem()
    {
        $doc=$this->tmp_db->table('tbl_one')->get(100);
        $this->assertInstanceOf(Document::class,$doc);
        $this->assertCount(0,$doc->toArray());
    }
    /** @test */
    public function testMustReturnDucomentInstanceOnExistItem()
    {
        $tbl=$this->tmp_db->table('tbl_one');
        $doc=$tbl->query()->create(['name'=>'John Doe']);

        $doc=$tbl->get(0);

        $this->assertInstanceOf(Document::class,$doc);
        $this->assertCount(1,$doc->toArray());
    }
    /** @test */
    public function testMustReturnDocumentInstanceOnCreateNewRecord()
    {
        $tbl=$this->tmp_db->table('tbfl_one');
        $doc=$tbl->query()->create(['name'=>'john']);
        $this->assertInstanceOf(Document::class,$doc);
    }
    /** @test */
    public function testMustReturnInstanceOfCollectionOnGetAll()
    {
        $this->fakeRecordCreator(5);
        $all=$this->tmp_db->table('tbl_name')->query()->getAll();
        $this->assertInstanceOf(Collection::class,$all);
        $this->assertCount(5,$all);
    }
    /** @test */
    public function testMust()
    {
        // Test
    }
    
}