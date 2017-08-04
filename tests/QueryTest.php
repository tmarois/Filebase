<?php  namespace Filebase;


class QueryTest extends \PHPUnit\Framework\TestCase
{

    /**
    * testWhereCountAllEqualCompare()
    *
    * TEST:
    *
    * 1. Creates 10 items in database with ["name" = "John"]
    * 2. Runs query to find items that have ["name" = "John"]
    * 3. Counts the total items in the database
    * 4. Compares the number of items in db to the number items the query found
    *
    * Results: Should be the EXACT SAME (query should find "all" items in db)
    *
    * Comparisons used "=", "==", "==="
    *
    */
    public function testWhereCountAllEqualCompare()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_1',
            'cache' => false
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name = 'John';
    		$user->save();
    	}

        $count  = $db->count();

        $query1 = $db->query()->where('name','=','John')->results();
        $query2 = $db->query()->where('name','==','John')->results();
        $query3 = $db->query()->where('name','===','John')->results();
        $query4 = $db->query()->where(['name' => 'John'])->results();

        $this->assertEquals($count, count($query1));
        $this->assertEquals($count, count($query2));
        $this->assertEquals($count, count($query3));
        $this->assertEquals($count, count($query4));

        $db->flush(true);
    }


    //--------------------------------------------------------------------


    /**
    * testWhereCountAllNotEqualCompare()
    *
    * TEST CASE:
    * - Creates 10 items in database with ["name" = "John"]
    * - Counts the total items in the database
    *
    * FIRST TEST:
    * - Compares the number of items in db to the number items the query found
    * - Should match "10"
    *
    * SECOND TEST:
    * - Secondary Tests to find items that DO NOT match "John"
    * - Should match "0"
    *
    * Comparisons used "!=", "!==", "NOT"
    *
    */
    public function testWhereCountAllNotEqualCompare()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_1',
            'cache' => false
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name = 'John';
    		$user->save();
    	}

        $count  = $db->count();

        $query1 = $db->query()->where('name','!=','Max')->results();
        $query2 = $db->query()->where('name','!==','Smith')->results();
        $query3 = $db->query()->where('name','NOT','Jason')->results();

        $query4 = $db->query()->where('name','!=','John')->results();
        $query5 = $db->query()->where('name','!==','John')->results();
        $query6 = $db->query()->where('name','NOT','John')->results();

        $this->assertEquals($count, count($query1));
        $this->assertEquals($count, count($query2));
        $this->assertEquals($count, count($query3));

        $this->assertEquals(0, count($query4));
        $this->assertEquals(0, count($query5));
        $this->assertEquals(0, count($query6));

        $db->flush(true);
    }


    //--------------------------------------------------------------------


    public function testWhereQueryWhereCount()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_1',
            'cache' => false
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name  = 'John';
            $user->email = 'john@example.com';
            $user->criteria = [
                'label' => 'lead'
            ];

    		$user->save();
    	}

        $results = $db->query()
    	 	->where('name','=','John')
            ->andWhere('email','==','john@example.com')
    		->results();

        $this->assertEquals($db->count(), count($results));

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



    public function testOrWhereQueryCount()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_1',
            'cache' => false
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
            ->orWhere('name','=','Max')
    		->results();

        $this->assertEquals($db->count(), count($results));

        $db->flush(true);
        $db->flushCache();
    }


    public function testWhereQueryFromCache()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_1',
            'cache' => true
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name  = 'John';
            $user->email = 'john@example.com';
    		$user->save();
    	}

        $results = $db->query()
    	 	->where('name','=','John')
            ->andWhere('email','==','john@example.com')
    		->resultDocuments();

        $result_from_cache = $db->query()
    	 	->where('name','=','John')
            ->andWhere('email','==','john@example.com')
    		->resultDocuments();

        $this->assertEquals(10, count($results));
        $this->assertEquals(true, ($result_from_cache[0]->isCache()));

        $db->flush(true);
        $db->flushCache();
    }

}
