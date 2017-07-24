<?php  namespace Flatfile;


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
    public function save()
    {
        return $this->__database->save($this);
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
        return $this->__database->set($data);
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
        // $this->{$name} = $this->__database->arrayToObject($value);
    }


    //--------------------------------------------------------------------


    /**
    * find
    *
    */
    public function find($key)
    {
        return $this->{$key};
    }


    //--------------------------------------------------------------------


    /**
    * getId
    *
    */
    public function getId()
    {
        return $this->__id;
    }


    //--------------------------------------------------------------------


    /**
    * setId
    *
    */
    public function setId($id)
    {
        $this->__id = $id;
    }


    //--------------------------------------------------------------------


    /**
    * createdAt
    *
    */
    public function createdAt($format = 'Y-m-d H:i:s')
    {
        if ($format != '') return date($format,$this->__created_at);

        return $this->__created_at;
    }


    //--------------------------------------------------------------------


    /**
    * createdAt
    *
    */
    public function updatedAt($format = 'Y-m-d H:i:s')
    {
        if ($format != '') return date($format,$this->__updated_at);

        return $this->__updated_at;
    }


    //--------------------------------------------------------------------


    /**
    * setCreatedAt
    *
    */
    public function setCreatedAt($created_at)
    {
        $this->__created_at = $created_at;
    }


    //--------------------------------------------------------------------


    /**
    * setuUpdatedAt
    *
    */
    public function setUpdatedAt($updated_at)
    {
        $this->__updated_at = $updated_at;
    }


    //--------------------------------------------------------------------

}
