<?php  namespace Filebase;


class DocumentTest extends \PHPUnit\Framework\TestCase
{

    public function testSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_save'
        ]);

        $test = $db->get('test')->set(['key'=>'value'])->save();

        $this->assertEquals(true, $test);
    }

    public function testArraySetValue()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $test = $db->get('test')->set(['key'=>'value']);

        $this->assertEquals('value', $test->key);
    }


    public function testPropertySetValue()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $test = $db->get('test');
        $test->key = 'value';

        $this->assertEquals('value', $test->key);
    }


    public function testArraySetValueSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test');

        $this->assertEquals('value', $test->key);
    }


    public function testPropertySetValueSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $test = $db->get('test');
        $test->key = 'value';
        $test->save();

        $test = $db->get('test');

        $this->assertEquals('value', $test->key);
    }


    public function testToArray()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->toArray();

        $this->assertEquals('value', $test['key']);
    }


    public function testDelete()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->delete();

        $this->assertEquals(true, $test);
    }


    public function testGetId()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test');

        $this->assertEquals('test', $test->getId());
    }


    public function testSetId()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->setId('newid');

        $this->assertEquals('newid', $test->getId());
    }


    public function testDates()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('test')->set(['key'=>'value'])->save();

        $createdAt = strtotime($db->get('test')->createdAt());
        $updatedAt = strtotime($db->get('test')->updatedAt());

        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$createdAt));
        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$updatedAt));
    }


    /*public function testFieldMethod()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_user_database'
        ]);

        $db->get('user_test_email_1')->set(['email'=>'example@example.com'])->save();

        $f = $db->get('user_test_email_1')->field('email');

        $this->assertEquals('example@example.com', $f);
    }*/


    /*public function testNestedFieldMethod()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/test_database'
        ]);

        $db->get('user_test_email_2')->set([
            'profile' => [
                'email' => 'example@example.com'
            ]
        ])->save();

        $f = $db->get('user_test_email_2')->field('profile.email');

        $this->assertEquals('example@example.com', $f);
    }*/

}
