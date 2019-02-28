<?php 
namespace Filebase;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use Filebase\Table;

class QueryTest extends TestCase
{
    public $db;
    public $query;

    public function setUp()
    {
        $this->root=vfsStream::setup('baseFolderName',null,['tbl_one'=>[],'tbl_two'=>[]]);
        $this->db=new Database([
            'path' => $this->root->url()
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