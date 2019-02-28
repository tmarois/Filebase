<?php 
namespace Filebase\Test;

use org\bovigo\vfs\vfsStream;
use Filebase\{Database,Table,Query};
use Filebase\Test\TestCase;
class QueryTest extends TestCase
{
    public $db;
    public $query;

    public function setUp()
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
        $tbl=$this->query->getTable();
        $this->assertInstanceOf(Table::class,$tbl);
    }
    /**
     * @test
     */
    public function testMustReturnInstanceOfDatabase()
    {
        $db=$this->query->getDatabase();
        $this->assertInstanceOf(Database::class,$db);
    }
   
}