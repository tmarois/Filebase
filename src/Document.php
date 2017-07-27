<?php  namespace Filebase;


class Document
{

    private $__database;
    private $__id;

    private $__created_at;
    private $__updated_at;

    private $data = [];


    /**
    * __construct
    *
    */
    public function __construct($database)
    {
        $this->__database = $database;
    }


    //--------------------------------------------------------------------


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
            if (in_array($k,['__database','__id'])) continue;
            $data->{$k} = $v;
        }

        return $data;
    }


    //--------------------------------------------------------------------


    /**
    * save
    *
    */
    public function save($data = '')
    {
        if (Validate::valid($this))
        {
            return $this->__database->save($this, $data);
        }

        return false;
    }


    //--------------------------------------------------------------------


    /**
    * delete
    *
    */
    public function delete()
    {
        return $this->__database->delete($this);
    }


    //--------------------------------------------------------------------


    /**
    * set
    *
    */
    public function set($data)
    {
        return $this->__database->set($this, $data);
    }


    //--------------------------------------------------------------------


    /**
    * toArray
    *
    */
    public function toArray()
    {
        return $this->__database->toArray($this);
    }


    //--------------------------------------------------------------------


    /**
    * __set
    *
    */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }


    //--------------------------------------------------------------------


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


    //--------------------------------------------------------------------


    /**
    * customFilter
    *
    * Allows you to run a custom function around each item
    *
    * @param string $field
    * @param callable $function
    * @return array $r items that the callable function returned
    */
    public function customFilter(string $field, callable $function)
    {
        $items = $this->{$field};

        if (!is_array($items) || empty($items))
        {
            return [];
        }

        $r = [];
        foreach($items as $item)
        {
            $i = $function($item);
            if ($i!==false || is_null($i)) {
                $r[] = $function($item);
            }
        }

        return $r;

    }


    //--------------------------------------------------------------------


    /**
    * getItemsFromCustomFilter
    *
    * currently NOT USED
    */
    public function getItemsFromCustomFilter($field = '')
    {
        $keys = Validate::hasCustomFilter($this);

        $data = $this->getData();

        foreach($keys as $k => $grp)
        {
            if ($grp == $field)
            {
                if ($data[$k])
                {
                    return $data[$k];
                }
            }
        }

        return [];
    }


    //--------------------------------------------------------------------


    /**
    * getDatabase
    *
    * @return $database
    */
    public function getDatabase()
    {
        return $this->__database;
    }


    //--------------------------------------------------------------------


    /**
    * getId
    *
    * @return mixed $__id
    */
    public function getId()
    {
        return $this->__id;
    }


    //--------------------------------------------------------------------


    /**
    * getData
    *
    * @return mixed data
    */
    public function getData()
    {
        return $this->data;
    }


    //--------------------------------------------------------------------


    /**
    * setId
    *
    * @param mixed $id
    */
    public function setId($id)
    {
        $this->__id = $id;
    }


    //--------------------------------------------------------------------


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
        if (!$this->__created_at) return false;

        if ($format !== false) return date($format, $this->__created_at);

        return $this->__created_at;
    }


    //--------------------------------------------------------------------


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
        if (!$this->__updated_at) return false;

        if ($format !== false) return date($format, $this->__updated_at);

        return $this->__updated_at;
    }


    //--------------------------------------------------------------------


    /**
    * setCreatedAt
    *
    * @param int $created_at php time()
    */
    public function setCreatedAt(int $created_at)
    {
        $this->__created_at = $created_at;
    }


    //--------------------------------------------------------------------


    /**
    * setuUpdatedAt
    *
    * @param int $updated_at php time()
    */
    public function setUpdatedAt(int $updated_at)
    {
        $this->__updated_at = $updated_at;
    }


    //--------------------------------------------------------------------


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
