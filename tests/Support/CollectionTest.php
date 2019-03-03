<?php 

use Filebase\Test\TestCase;
use Filebase\Support\Collection;
/**
 * ClassNameTest
 * @group group
 */
class CollectionTest extends TestCase
{
    /** @test */
    public function testMustReturnArrayTypeOnCollectionToArray()
    {
        $result=new Collection([1,2,3,4,5]);
        $this->assertInternalType('array', $result->toArray());
    }
    /** @test */
    public function testMustReturnArrayTypeOnCollectionToArrayWithObjectItem()
    {
        $tbl=$this->fakeRecordCreator(5);
        $result=$tbl->query()->findMany([1,2,3])->toArray();
        // check 
        $this->assertInternalType('array', $result[0]);
        $this->assertCount(3,$result);
    }
}
