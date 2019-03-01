<?php
namespace Filebase\Test;

use Filebase\Test\TestCase;
use Filebase\Document;
use Countable;
/**
 * ClassNameTest
 * @group group
 */
class DocumentTest extends TestCase
{
    /** @test */
    public function testArrayAccess()
    {
        $doc=$this->tmp_db->table('tbl_name')->get(0);
        
        // check countable
        $this->assertTrue($doc instanceof \Countable);
        $doc['name']='john';
        $doc['last_name']='Doe';
        $this->assertCount(2,$doc);
    }
    /** @test */
    public function testMustUpdateRecordOnDocumentWithArrayMode()
    {
        $doc=$this->tmp_db->table('tbl_name')->get(0);
        
        $doc['name']='john';
        $doc['last_name']='Doe';
        $doc->save();
        $doc=$this->tmp_db->table('tbl_name')->get(0);
        $this->assertArrayHasKey('last_name',$doc);

        $this->assertEquals(['name'=>'john','last_name'=>'Doe'],$doc->toArray());
        $doc['name']='faryar';
        $doc['last_name']='Doe';
        $doc->save();
        $this->assertEquals(['name'=>'faryar','last_name'=>'Doe'],$doc->toArray());
    }

    /** @test */
    public function testMustReturnUpdatedDocumentInstance()
    {
        $doc=$this->tmp_db->table('tbl_name')->get(0);
        
        $doc['name']='john';
        $doc['last_name']='Doe';
        $doc=$doc->save();
        $this->assertInstanceOf(Document::class,$doc);
    }
    /** @test */
    public function testMustRemoveRecord()
    {
        $doc=$this->tmp_db->table('tbl_name')->query()->create(['name'=>'John']);
        $this->assertFileExists($this->path."/tbl_name/0.json");
        $this->assertTrue($doc->delete());
        $this->assertFileNotExists($this->path."/tbl_name/0.json");
    }
    
}
