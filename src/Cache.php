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
    * $key
    *
    */
    protected $key;


    //--------------------------------------------------------------------

    /**
    * __construct()
    *
    */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }


    //--------------------------------------------------------------------


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


    //--------------------------------------------------------------------


    /**
    * getKey()
    *
    */
    public function getKey()
    {
        return $this->key;
    }


    //--------------------------------------------------------------------


    /**
    * flush()
    *
    */
    public function flush()
    {
        return false;
    }


    //--------------------------------------------------------------------


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

        return false;
    }


    //--------------------------------------------------------------------


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
    }


    //--------------------------------------------------------------------


}
