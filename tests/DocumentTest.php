<?php  namespace Filebase;


class DocumentTest extends \PHPUnit\Framework\TestCase
{
    public function testSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $test = $db->get('test')->set(['key'=>'value'])->save();

        $this->assertEquals(true, $test);
    }


    public function testArraySetValue()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $test = $db->get('test')->set(['key'=>'value']);

        $this->assertEquals('value', $test->key);
    }


    public function testPropertySetValue()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $test = $db->get('test');
        $test->key = 'value';

        $this->assertEquals('value', $test->key);
    }


    public function testPropertySetValueNull()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $test = $db->get('test');

        $this->assertEquals(null, $test->key);
    }


    public function testArraySetValueSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test');

        $this->assertEquals('value', $test->key);
    }


    public function testPropertySetValueSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $test = $db->get('test');
        $test->key = 'value';
        $test->save();

        $test = $db->get('test');

        $this->assertEquals('value', $test->key);
    }


    public function testToArray()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->toArray();

        $this->assertEquals('value', $test['key']);
    }


    public function testDelete()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->delete();

        $this->assertEquals(true, $test);
    }


    public function testGetId()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test');

        $this->assertEquals('test', $test->getId());
    }


    public function testSetId()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->setId('newid');

        $this->assertEquals('newid', $test->getId());
    }


    public function testDates()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $createdAt = strtotime($db->get('test')->createdAt());
        $updatedAt = strtotime($db->get('test')->updatedAt());

        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$createdAt));
        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$updatedAt));
    }


    public function testFormatDates()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $createdAt = $db->get('test')->createdAt('Y-m-d');
        $updatedAt = $db->get('test')->updatedAt('Y-m-d');

        $this->assertEquals(date('Y-m-d'), $createdAt);
        $this->assertEquals(date('Y-m-d'), $updatedAt);
    }


    public function testNoFormatDates()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $createdAt = $db->get('test')->createdAt(false);
        $updatedAt = $db->get('test')->updatedAt(false);

        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$createdAt));
        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$updatedAt));
    }


    public function testCustomFilter()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $u = [];
        $u[] = [
            'email' => 'email@email.com',
            'status' => 'blocked'
        ];

        $u[] = [
            'email' => 'notblocked@email.com',
            'status' => 'enabled'
        ];

        $db->get('users')->set($u)->save();

        $users = $db->get('users')->customFilter('data',function($item) {
            return (($item['status']=='blocked') ? $item['email'] : false);
        });

        $this->assertEquals(1, count($users));
        $this->assertEquals('email@email.com', $users[0]);
    }


    public function testCustomFilterEmpty()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $u = [
            'email' => 'email@email.com',
            'status' => 'blocked'
        ];

        $db->get('customfilter_test')->set($u)->save();

        $users = $db->get('customfilter_test')->customFilter('data',function($item) {
            return ((isset($item['status']) && $item['status']=='blocked') ? $item['email'] : false);
        });

        // should be empty array
        $this->assertEquals([],$users);
    }


    public function testFieldMethod()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('user_test_email_1')->set(['email'=>'example@example.com'])->save();

        $f = $db->get('user_test_email_1')->field('email');

        $this->assertEquals('example@example.com', $f);
    }


    public function testNestedFieldMethod()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $db->get('user_test_email_2')->set([
            'profile' => [
                'email' => 'example@example.com'
            ]
        ])->save();

        $f = $db->get('user_test_email_2')->field('profile.email');

        $this->assertEquals('example@example.com', $f);
    }


    public function testBadNameException()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases'
        ]);

        $db->flush(true);

        $file = $db->get('^*bad_@name%$1#');
    }

}
