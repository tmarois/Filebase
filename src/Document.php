<?php  namespace Filebase;


class Document
{

    private $__database;
    private $__id;

    private $__created_at;
    private $__updated_at;

    private $__cache = false;

    private $data = [];


    /**
    * __construct
    *
    * Sets the database property
    */
    public function __construct($database)
    {
        $this->__database = $database;
    }

    /**
    * saveAs
    *
    */
    public function saveAs()
    {
        $data = (object) [];
        $vars = get_object_vars($this);

        foreach($vars as $k=>$v)
        {
            if (in_array($k,['__database','__id','__cache'])) continue;
            $data->{$k} = $v;
        }

        return $data;
    }

    /**
    * save()
    *
    * Saving the document to disk (file)
    *
    * @param mixed $data (optional, only if you want to "replace" entire doc data)
    * @return @see \Filebase\Database save()
    */
    public function save($data = '')
    {
        Validate::valid($this);

        return $this->__database->save($this, $data);
    }

    /**
    * delete
    *
    * Deletes document from disk (file)
    *
    * @return @see \Filebase\Database delete()
    */
    public function delete()
    {
        return $this->__database->delete($this);
    }

    /**
    * set
    *
    */
    public function set($data)
    {
        return $this->__database->set($this, $data);
    }

    /**
    * toArray
    *
    */
    public function toArray()
    {
        return $this->__database->toArray($this);
    }

    /**
    * __set
    *
    */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
    * __get
    *
    */
    public function &__get($name)
    {
        if (!array_key_exists($name, $this->data))
        {
            $this->data[$name] = null;
        }

        return $this->data[$name];
    }

    /**
    * __isset
    *
    */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
    * __unset
    *
    */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }


    //--------------------------------------------------------------------


    /**
    * filter
    *
    * Alias of customFilter
    *
    * @see customFilter
    */
    public function filter($field = 'data', $paramOne = '', $paramTwo = '')
    {
        return $this->customFilter($field, $paramOne, $paramTwo);
    }

    /**
    * customFilter
    *
    * Allows you to run a custom function around each item
    *
    * @param string $field
    * @param callable $function
    * @return array $r items that the callable function returned
    */
    public function customFilter($field = 'data', $paramOne = '', $paramTwo = '')
    {
        $items = $this->field($field);

        if (is_callable($paramOne))
        {
            $function = $paramOne;
            $param = $paramTwo;
        }
        else
        {
            if (is_callable($paramTwo))
            {
                $function = $paramTwo;
                $param = $paramOne;
            }
        }


        if (!is_array($items) || empty($items))
        {
            return [];
        }

        $r = [];
        foreach($items as $index => $item)
        {
            $i = $function($item, $param);

            if ($i!==false && !is_null($i))
            {
                $r[$index] = $i;
            }
        }

        $r = array_values($r);

        return $r;

    }

    /**
    * getDatabase
    *
    * @return $database
    */
    public function getDatabase()
    {
        return $this->__database;
    }

    /**
    * getId
    *
    * @return mixed $__id
    */
    public function getId()
    {
        return $this->__id;
    }

    /**
    * getData
    *
    * @return mixed data
    */
    public function getData()
    {
        return $this->data;
    }

    /**
    * setId
    *
    * @param mixed $id
    */
    public function setId($id)
    {
        $this->__id = $id;

        return $this;
    }

    /**
    * setCache
    *
    * @param boolean $cache
    */
    public function setFromCache($cache = true)
    {
        $this->__cache = $cache;

        return $this;
    }

    /**
    * isCache
    *
    */
    public function isCache()
    {
        return $this->__cache;
    }

    /**
    * createdAt
    *
    * When this document was created (or complete replaced)
    *
    * @param string $format php date format (default Y-m-d H:i:s)
    * @return string date format
    */
    public function createdAt($format = 'Y-m-d H:i:s')
    {
        if (!$this->__created_at)
        {
            return date($format);
        }

        if ($format !== false)
        {
            return date($format, $this->__created_at);
        }

        return $this->__created_at;
    }

    /**
    * updatedAt
    *
    * When this document was updated
    *
    * @param string $format php date format (default Y-m-d H:i:s)
    * @return string date format
    */
    public function updatedAt($format = 'Y-m-d H:i:s')
    {
        if (!$this->__updated_at)
        {
            return date($format);
        }

        if ($format !== false)
        {
            return date($format, $this->__updated_at);
        }

        return $this->__updated_at;
    }

    /**
    * setCreatedAt
    *
    * @param int $created_at php time()
    */
    public function setCreatedAt($created_at)
    {
        $this->__created_at = $created_at;

        return $this;
    }

    /**
    * setuUpdatedAt
    *
    * @param int $updated_at php time()
    */
    public function setUpdatedAt($updated_at)
    {
        $this->__updated_at = $updated_at;

        return $this;
    }

    /**
    * field
    *
    * Gets property based on a string
    *
    * You can also use string separated by dots for nested arrays
    * key_1.key_2.key_3 etc
    *
    * @param string $field
    * @return string $context property
    */
    public function field($field)
    {
        $parts   = explode('.', $field);
        $context = $this->data;

        if ($field=='data') {
            return $context;
        }

        if ($field == '__created_at') {
            return $this->__created_at;
        }

        if ($field == '__updated_at') {
            return $this->__updated_at;
        }

        if ($field == '__id') {
            return $this->__id;
        }

        foreach($parts as $part)
        {
            if (trim($part) == '')
            {
                return false;
            }

            if (is_object($context))
            {
                if(!property_exists($context, $part))
                {
                    return false;
                }

                $context = $context->{$part};
            }
            else if (is_array($context))
            {
                if(!array_key_exists($part, $context))
                {
                    return false;
                }

                $context = $context[$part];
            }
        }

        return $context;
    }

}
