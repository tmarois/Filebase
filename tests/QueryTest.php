<?php  namespace Filebase;


class QueryTest extends \PHPUnit\Framework\TestCase
{

    public function testWhereQueryCount()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_1'
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name = 'John';

    		$user->save();
    	}

        $results = $db->query()
    	 	->where('name','=','John')
    		->results();

        $this->assertEquals(10, $db->count());

        $db->flush(true);
        $db->flushCache();
    }


    public function testWhereQueryFindNameCount()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_2'
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());

            if ($x < 6)
            {
                $user->name = 'John';
            }
            else
            {
                $user->name = 'Max';
            }

    		$user->save();
    	}

        $results = $db->query()
    	 	->where('name','=','John')
    		->results();

        $this->assertEquals(5, count($results));

        $db->flush(true);
        $db->flushCache();
    }

}
