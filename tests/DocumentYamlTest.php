<?php  namespace Filebase;


class DocumentYamlTest extends \PHPUnit\Framework\TestCase
{

    /**
    * testSave()
    *
    * TEST CASE:
    * - Save document with data
    * - Get the document
    * - Check that the data is there and the document exist
    *
    */
    public function testSave()
    {
        $db = new \Filebase\Database([
            'dir'   => __DIR__.'/databases',
            'cache' => false,
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        // save data
        $doc = $db->get('test_save')->save(['key'=>'value']);

        // get saved data (put into array)
        $val = $db->get('test_save');

        // should equal...
        $this->assertEquals('value', $val->key);

        #$db->flush(true);
    }


    //--------------------------------------------------------------------




        /**
        * testDoesNotExist()
        *
        * TEST CASE:
        * - Save document with data
        * - Get the document
        * - Check that the data is there and the document exist
        *
        */
        public function testDoesNotExist()
        {
            $db = new \Filebase\Database([
                'dir'   => __DIR__.'/databases',
                'cache' => false,
                'format' => \Filebase\Format\Yaml::class
            ]);

            $db->flush(true);

            // get saved data (put into array)
            $doc = $db->get('doesexist')->save(['key'=>'value']);

            $this->assertEquals(true, $db->has('doesexist'));

            $this->assertEquals(false, $db->has('doesnotexist'));

            $db->flush(true);
        }


        //--------------------------------------------------------------------




    /**
    * testSetIdGetId()
    *
    * TEST CASE:
    * - Set and Get Id
    *
    */
    public function testSetIdGetId()
    {
        $db = new \Filebase\Database([
            'dir'   => __DIR__.'/databases/data_rename',
            'cache' => false,
            'format' => \Filebase\Format\Yaml::class
        ]);

        // save data
        $doc = $db->get('name_1')->save(['key'=>'value']);
        $this->assertEquals('name_1', $doc->getId());

        // delete existing doc so its not duplicated
        // object still exist, but file has been removed
        $doc->delete();
        $this->assertEquals('name_1', $doc->getId());

        // change id and save (new file is created)
        $doc->setId('name_2')->save();
        $this->assertEquals('name_2', $doc->getId());
    }


    //--------------------------------------------------------------------


    /**
    * testSetValue()
    *
    * TEST CASE:
    * - Using the set method, set the value in object ( DO NOT SAVE )
    * - Check that the properties are in the object (matching)
    *
    */
    public function testSetValue()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'cache' => false,
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        // FIRST TEST
        // use the set() method
        $test1 = $db->get('test1')->set(['key'=>'value']);

        $this->assertEquals('value', $test1->key);


        // SECOND TEST:
        // use the property setter
        $test2 = $db->get('test2');
        $test2->key = 'value';

        $this->assertEquals('value', $test2->key);


        // THIRD TEST (null test)
        $test3 = $db->get('test3');

        $this->assertEquals(null, $test3->key);

    }


    //--------------------------------------------------------------------


    /**
    * testIssetUnsetUnknown()
    *
    * TEST CASE:
    * - Check if property isset
    * - Unset property and see if it now returns null
    *
    */
    public function testIssetUnset()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'cache' => false,
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $test = $db->get('test2');
        $test->key = 'value';

        $this->assertEquals('value', $test->key);

        $this->assertEquals(1, isset($test->key));

        unset($test->key);

        $this->assertEquals(null, ($test->key));

    }


    //--------------------------------------------------------------------


    public function testArraySetValueSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test');

        $this->assertEquals('value', $test->key);

        $db->flush(true);
    }


    public function testPropertySetValueSave()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $test = $db->get('test');
        $test->key = 'value';
        $test->save();

        $test = $db->get('test');

        $this->assertEquals('value', $test->key);

        $db->flush(true);
    }


    public function testToArray()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->toArray();

        $this->assertEquals('value', $test['key']);

        $db->flush(true);
    }


    public function testDelete()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->delete();

        $this->assertEquals(true, $test);

        $db->flush(true);
    }


    public function testGetId()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test');

        $this->assertEquals('test', $test->getId());

        $db->flush(true);
    }


    public function testSetId()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $test = $db->get('test')->setId('newid');

        $this->assertEquals('newid', $test->getId());

        $db->flush(true);
    }



    // DATE TESTS
    //--------------------------------------------------------------------

    public function testDates()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $createdAt = strtotime($db->get('test')->createdAt());
        $updatedAt = strtotime($db->get('test')->updatedAt());

        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$createdAt));
        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$updatedAt));

        $db->flush(true);
    }


    public function testFormatDates()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $createdAt = $db->get('test')->createdAt('Y-m-d');
        $updatedAt = $db->get('test')->updatedAt('Y-m-d');

        $this->assertEquals(date('Y-m-d'), $createdAt);
        $this->assertEquals(date('Y-m-d'), $updatedAt);

        $db->flush(true);
    }


    public function testNoFormatDates()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $createdAt = $db->get('test')->createdAt(false);
        $updatedAt = $db->get('test')->updatedAt(false);

        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$createdAt));
        $this->assertEquals(date('Y-m-d'), date('Y-m-d',$updatedAt));

        $db->flush(true);
    }


    public function testMissingUpdatedDate()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('test')->set(['key'=>'value'])->save();

        $setUpdatedAt = $db->get('test')->setUpdatedAt(null);
        $setCreatedAt = $db->get('test')->setCreatedAt(null);

        $this->assertEquals(date('Y-m-d'), $setCreatedAt->updatedAt('Y-m-d'));
        $this->assertEquals(date('Y-m-d'), $setUpdatedAt->updatedAt('Y-m-d'));

        $db->flush(true);
    }


    //--------------------------------------------------------------------


    public function testCustomFilter()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
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

        $db->flush(true);
    }


    public function testCustomFilterParam()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
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

        $users = $db->get('users')->customFilter('data','enabled',function($item, $status) {
            return (($item['status']==$status) ? $item['email'] : false);
        });

        $this->assertEquals(1, count($users));
        $this->assertEquals('notblocked@email.com', $users[0]);

        $db->flush(true);
    }



    public function testCustomFilterParamIndex()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $u = [];
        $u[] = [
            'email' => 'enabled-email@email.com',
            'id' => '123',
            'status' => 'deactive'
        ];

        $u[] = [
            'email' => 'enabled-email@email.com',
            'id' => '321',
            'status' => 'enabled'
        ];

        $db->get('users_test_custom')->save($u);

        $users = $db->get('users_test_custom')->filter('data','enabled',function($item, $status) {
            return (($item['status']==$status) ? $item : false);
        });


        $this->assertEquals(1, count($users));
        $this->assertEquals('enabled-email@email.com', $users[0]['email']);

        $db->flush(true);
    }


    public function testCustomFilterEmpty()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('customfilter_test')->set(['email'=>'time'])->save();

        $users = $db->get('customfilter_test')->customFilter('email',function($item) {
            return (($item['status']=='blocked') ? $item['email'] : false);
        });

        // should be empty array
        $this->assertEquals([],$users);

        $db->flush(true);
    }


    public function testFieldMethod()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('user_test_email_1')->set(['email'=>'example@example.com'])->save();

        $f = $db->get('user_test_email_1')->field('email');

        $this->assertEquals('example@example.com', $f);

        $db->flush(true);
    }


    public function testNestedFieldMethod()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $db->get('user_test_email_2')->set([
            'profile' => [
                'email' => 'example@example.com'
            ]
        ])->save();

        $f = $db->get('user_test_email_2')->field('profile.email');

        $this->assertEquals('example@example.com', $f);

        $db->flush(true);
    }


    public function testBadNameException()
    {
        $this->expectException(\Exception::class);

        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases',
            'safe_filename' => false,
            'format' => \Filebase\Format\Yaml::class
        ]);

        $db->flush(true);

        $file = $db->get('^*bad_@name%$1#');

        $db->flush(true);
    }


    public function testBadNameReplacement()
    {
        $badName = 'ti^@%mo!!~th*y-m_?a(ro%)is.&';
        $newName = Filesystem::validateName($badName, true);

        $this->assertEquals('timothy-m_arois', $newName);
    }


    public function testBadNameReplacementLong()
    {
        $badName = '1234567890123456789012345678901234567890123456789012345678901234';
        $newName = Filesystem::validateName($badName, true);

        $this->assertEquals(63, (strlen($newName)) );
        $this->assertEquals('123456789012345678901234567890123456789012345678901234567890123', $newName);
    }

}
