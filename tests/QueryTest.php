<?php namespace Filebase;

use Exception;
use Filebase\Database;
use Base\Support\Filesystem;

class QueryTest extends \PHPUnit\Framework\TestCase
{

    /**
    * testDatabaseCountZeroNoErrors()
    *
    * TEST:
    * (1) Test database-table COUNT() zero
    *     BUT hide errors from throwing when does not exist
    *
    */
    public function testWhereQuery()
    {
        Filesystem::deleteDirectory(__DIR__.'/database');

        $db = new Database([
            'path' => __DIR__.'/database'
        ]);


        $tDb = $db->table('products');

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $tDb->get(uniqid());
    		$user->name = 'John';
            $user->number = $x;
            $user->contact['email'] = 'john@john.com';
    		$user->save();
    	}

        $this->assertEquals(10, $tDb->count());

        $query1 = $tDb->where(['name' => 'John', 'number' => 1])->first();


        /*$query2 = $tDb->where(['name' => 'John'],function($q){
            $q->where(['number' => 1], function($q){
                $q->where(['email' => 'john@john.com']);
            });
        })->results();*/


        // SELECT table FROM (name = John AND number = 1)

        // print_r($query1);

        Filesystem::deleteDirectory(__DIR__.'/database');
    }

}
