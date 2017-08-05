<?php  namespace Filebase;


class QueryTest extends \PHPUnit\Framework\TestCase
{

    /**
    * testWhereCountAllEqualCompare()
    *
    * TEST CASE:
    * - Creates 10 items in database with ["name" = "John"]
    * - Counts the total items in the database
    *
    * FIRST TEST (standard matches):
    * - Compares the number of items in db to the number items the query found
    * - Should match "10"
    *
    * SECOND TEST (nested arrays)
    * - Tests the inner array level field findings ["about" => ["name" => "Roy"] ])
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

        // FIRST TEST
        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name = 'John';
            $user->contact['email'] = 'john@john.com';
    		$user->save();
    	}

        $count  = $db->count();

        // standard matches
        $query1 = $db->query()->where('name','=','John')->results();
        $query2 = $db->query()->where('name','==','John')->results();
        $query3 = $db->query()->where('name','===','John')->results();
        $query4 = $db->query()->where(['name' => 'John'])->results();

        // testing nested level
        $query5 = $db->query()->where('contact.email','=','john@john.com')->results();

        $this->assertEquals($count, count($query1));
        $this->assertEquals($count, count($query2));
        $this->assertEquals($count, count($query3));
        $this->assertEquals($count, count($query4));
        $this->assertEquals($count, count($query5));

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



    /**
    * testWhereCountAllGreaterLessCompare()
    *
    * TEST CASE:
    * - Creates 10 items in database with ["pages" = 5]
    * - Counts the total items in the database
    *
    * FIRST TEST: Greater Than
    * - Should match "10"
    *
    * SECOND TEST: Less Than
    * - Should match "10"
    *
    * THIRD TEST: Less/Greater than "no match"
    * - Should match "0"
    *
    * Comparisons used ">=", ">", "<=", "<"
    *
    */
    public function testWhereCountAllGreaterLessCompare()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_1',
            'cache' => false
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->pages = 5;
    		$user->save();
    	}

        $count  = $db->count();

        // FIRST TEST
        $query1 = $db->query()->where('pages','>','4')->results();
        $query2 = $db->query()->where('pages','>=','5')->results();

        // SECOND TEST
        $query3 = $db->query()->where('pages','<','6')->results();
        $query4 = $db->query()->where('pages','<=','5')->results();

        // THIRD TEST
        $query5 = $db->query()->where('pages','>','5')->results();
        $query6 = $db->query()->where('pages','<','5')->results();

        $this->assertEquals($count, count($query1));
        $this->assertEquals($count, count($query2));
        $this->assertEquals($count, count($query3));
        $this->assertEquals($count, count($query4));
        $this->assertEquals(0, count($query5));
        $this->assertEquals(0, count($query6));

        $db->flush(true);
    }


    //--------------------------------------------------------------------


    /**
    * testWhereLikeRegex()
    *
    * TEST CASE:
    * - Creates a bunch of items with the same information
    * - Creates one item with different info (finding the needle)
    *
    * Comparisons used "LIKE", "NOT LIKE", "=="
    *
    */
    public function testWhereLikeRegex()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_like',
            'cache' => false
        ]);

        $db->flush(true);

        for ($x = 1; $x <= 10; $x++)
    	{
    		$user = $db->get(uniqid());
    		$user->name  = 'John Ellot';
            $user->email = 'johnellot@example.com';
    		$user->save();
    	}

        // the needle
        $user = $db->get(uniqid());
        $user->name  = 'Timothy Marois';
        $user->email = 'timothymarois@email.com';
        $user->save();

        $count  = $db->count();

        // should return exact match
        $query1 = $db->query()->where('name','==','Timothy Marois')->results();
        // this should fail to find anything
        $query2 = $db->query()->where('name','==','timothy marois')->results();

        // this should find match with regex loose expression
        $query3 = $db->query()->where('name','LIKE','timothy marois')->results();
        // this should find match by looking for loose expression on "timothy"
        $query4 = $db->query()->where('name','LIKE','timothy')->results();
        // this should find all teh users that have an email address using "@email.com"
        $query5 = $db->query()->where('email','LIKE','@email.com')->results();
        // this should return 1 as its looking at only the emails not like "@example.com"
        $query6 = $db->query()->where('email','NOT LIKE','@example.com')->results();

        $this->assertEquals(1, count($query1));
        $this->assertEquals(0, count($query2));
        $this->assertEquals(1, count($query3));
        $this->assertEquals(1, count($query4));
        $this->assertEquals(1, count($query5));
        $this->assertEquals(1, count($query6));

        $db->flush(true);
    }


    //--------------------------------------------------------------------


    /**
    * testLimitOffset()
    *
    * TEST CASE:
    * - Creates 6 company profiles
    * - Queries them and limits the results
    *
    *
    */
    public function testLimitOffset()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_orderby',
            'cache' => false
        ]);

        $db->flush(true);

        $companies = ['Google'=>150, 'Apple'=>150, 'Microsoft'=>150, 'Amex'=>150, 'Hooli'=>20, 'Amazon'=>10];

        foreach($companies as $company=>$rank)
        {
            $user = $db->get(uniqid());
    		$user->name  = $company;
            $user->rank  = $rank;
    		$user->save();
        }

        // test that it limits the results to "2" (total query pulls "5")
        $test1 = $db->query()->where('rank','=',150)->limit(2)->results();

        // test the offset, no limit, should be 3 (total query pulls "5")
        $test2 = $db->query()->where('rank','=',150)->limit(0,1)->results();

        // test that the offset takes off the first array (should return "apple", not "google")
        $test3 = $db->query()->where('rank','=',150)->limit(1,1)->results();

        $this->assertEquals(2, (count($test1)));
        $this->assertEquals(3, (count($test2)));
        $this->assertEquals('Apple', $test3[0]['name']);

        $db->flush(true);
    }


    //--------------------------------------------------------------------


    /**
    * testSorting()
    *
    * TEST CASE:
    * - Creates 6 company profiles
    * - Sorts them by DESC/ASC
    *
    *
    */
    public function testSorting()
    {
        $db = new \Filebase\Database([
            'dir' => __DIR__.'/databases/users_orderby',
            'cache' => false
        ]);

        $db->flush(true);

        $companies = ['Google'=>150, 'Apple'=>180, 'Microsoft'=>120, 'Amex'=>20, 'Hooli'=>50, 'Amazon'=>140];

        foreach($companies as $company=>$rank)
        {
            $user = $db->get(uniqid());
    		$user->name    = $company;
            $user->rank['reviews'] = $rank;
            $user->status  = 'enabled';
    		$user->save();
        }

        // test that they are ordered by name ASC (check first, second, and last)
        $test1 = $db->query()->where('status','=','enabled')->orderBy('name', 'ASC')->results();
        $this->assertEquals(['first'=>'Amazon','second'=>'Amex','last'=>'Microsoft'], ['first'=>$test1[0]['name'],'second'=>$test1[1]['name'],'last'=>$test1[5]['name']]);

        // test that they are ordered by name ASC (check first, second, and last)
        $test2 = $db->query()->where('status','=','enabled')->limit(3)->orderBy('name', 'ASC')->results();
        $this->assertEquals(['Amazon','Amex','Apple'], [$test2[0]['name'],$test2[1]['name'],$test2[2]['name']]);

        // test that they are ordered by name DESC (check first, second, and last)
        $test3 = $db->query()->where('status','=','enabled')->limit(3)->orderBy('name', 'DESC')->results();
        $this->assertEquals(['Microsoft','Hooli','Google'], [$test3[0]['name'],$test3[1]['name'],$test3[2]['name']]);

        // test that they are ordered by rank nested [reviews] DESC
        $test4 = $db->query()->where('status','=','enabled')->limit(3)->orderBy('rank.reviews', 'DESC')->results();
        $this->assertEquals(['Apple','Google','Amazon'], [$test4[0]['name'],$test4[1]['name'],$test4[2]['name']]);

        // test that they are ordered by rank nested [reviews] ASC
        $test5 = $db->query()->where('status','=','enabled')->limit(3)->orderBy('rank.reviews', 'ASC')->results();
        $this->assertEquals(['Amex','Hooli','Microsoft'], [$test5[0]['name'],$test5[1]['name'],$test5[2]['name']]);

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
