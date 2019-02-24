<?php  namespace Filebase;


class Cache
{

    /**
    * $database
    *
    * \Filebase\Database
    */
    protected $database;


    /**
    * $cache_database
    *
    * \Filebase\Database
    */
    protected $cache_database;


    /**
    * $key
    *
    */
    protected $key;

    /**
    * __construct()
    *
    */
    public function __construct(Database $database)
    {
        $this->database = $database;

        $this->cache_database  = new \Filebase\Database([
    		'dir' => $this->database->getConfig()->dir.'/__cache',
            'cache' => false,
            'pretty' => false
    	]);
    }

    /**
    * setKey()
    *
    * This key is used to identify the cache
    * and know how to call the cache again
    *
    */
    public function setKey($key)
    {
        $this->key = md5($key);
    }

    /**
    * getKey()
    *
    */
    public function getKey()
    {
        return $this->key;
    }

    /**
    * flush()
    *
    */
    public function flush()
    {
        $this->cache_database->flush(true);
    }

    /**
    * expired()
    *
    * @param $time (date format)
    * @return bool (true/false)
    */
    public function expired($time)
    {
        if ( (strtotime($time)+$this->database->getConfig()->cache_expires) > time() )
        {
            return false;
        }

        return true;
    }

    /**
    * getDocuments()
    *
    */
    public function getDocuments($documents)
    {
        $d = [];
        foreach($documents as $document)
        {
            $d[] = $this->database->get($document)->setFromCache(true);
        }

        return $d;
    }

    /**
    * get()
    *
    */
    public function get()
    {
        if (!$this->getKey())
        {
            throw new \Exception('You must supply a cache key using setKey to get cache data.');
        }

        $cache_doc = $this->cache_database->get( $this->getKey() );

        if (!$cache_doc->toArray())
        {
            return false;
        }

        if ( $this->expired( $cache_doc->updatedAt() ) )
        {
            return false;
        }

        return $this->getDocuments($cache_doc->toArray());
    }

    /**
    * store()
    *
    */
    public function store($data)
    {
        if (!$this->getKey())
        {
            throw new \Exception('You must supply a cache key using setKey to store cache data.');
        }

        return $this->cache_database->get( $this->getKey() )->set($data)->save();
    }

}
