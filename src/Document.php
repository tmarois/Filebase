<?php  namespace Filebase;


class Document
{

    private $__database;
    private $__id;

    private $__created_at;
    private $__updated_at;


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
        return $this->__database->save($this,$data);
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
        return $this->__database->set($this,$data);
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
        if (is_array($value)) $value = (object) $value;
        $this->{$name} = $value;
    }


    //--------------------------------------------------------------------


    /**
    * find
    *
    */
    public function find($key)
    {
        return (isset($this->{$key}) ? $this->{$key} : null);
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

        if ($format !== false) return date($format,$this->__created_at);

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

        if ($format !== false) return date($format,$this->__updated_at);

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

}
