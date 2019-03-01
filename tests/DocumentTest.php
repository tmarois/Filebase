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
}
