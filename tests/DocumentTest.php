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
        $doc=$this->tmp_db->table('tbl_name')->query()->find(0);
        
        // check countable
        $this->assertTrue($doc instanceof \Countable);
        $doc['name']='john';
        $doc['last_name']='Doe';
        $this->assertCount(2,$doc);
    }
    /** @test */
    public function testMustUpdateRecordOnDocumentWithArrayMode()
    {
        $doc=$this->tmp_db->table('tbl_name')->query()->find(0);
        
        $doc['name']='john';
        $doc['last_name']='Doe';
        $doc->save();
        $doc=$this->tmp_db->table('tbl_name')->query()->find(0);
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
        $doc=$this->tmp_db->table('tbl_name')->query()->find(0);
        
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
    /** @test */
    public function testMustUpdateDocumentWithUpdateMethod()
    {
        $tbl=$this->tmp_db->table('tbl_name');
        $doc=$tbl->query()->create(['Foo'=>'bar']);
        $this->assertEquals(['Foo'=>'bar'],$doc->toArray());
        $doc->update(['Foo'=>'faryar']);
        $this->assertEquals(['Foo'=>'faryar'],$doc->toArray());
    }
    /** @test */
    public function testMustUpdateJustNewKeysOnSaveMethod()
    {
        $tbl=$this->tmp_db->table('tbl_name');
        $doc=$tbl->query()->create(['email'=>'foo@bar','name'=>'faryar']);
        $doc->name='tmarios';
        $doc->save();
        $this->assertEquals(['email'=>'foo@bar','name'=>'tmarios'],$doc->toArray());
    }
    /** @test */
    public function testMustUpdateJustNewKeysOnUpdateMethod()
    {
        $tbl=$this->tmp_db->table('tbl_name');
        $doc=$tbl->query()->create(['email'=>'foo@bar','name'=>'faryar']);
        $doc->update(['name'=>'tmarios']);
        $this->assertEquals(['email'=>'foo@bar','name'=>'tmarios'],$doc->toArray());
    }
    
    
}
