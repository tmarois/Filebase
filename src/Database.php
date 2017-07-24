<?php  namespace Flatfile;


class Database
{

    protected $config;

    protected $document;


    /**
    * __construct
    *
    */
    public function __construct(\Flatfile\Config $config)
    {
        $this->config = $config;
    }


    //--------------------------------------------------------------------


    /**
    * findAll
    *
    */
    public function findAll($open = false)
    {
        $file_extension = $this->config->format::getFileExtension();
        $file_location  = $this->config->database.'/';

        $all = \Flatfile\Filesystem::getAllFiles($file_location,$file_extension);
        if ($open==true)
        {
            $items = [];

            foreach($all as $a)
        	{
        		$items[] = $this->get($a);
        	}

            return $items;
        }

        return $all;
    }


    //--------------------------------------------------------------------


    /**
    * get
    *
    *
    */
    public function get($id)
    {
        $data = $this->read($id);
        $this->document = new \Flatfile\Document($this);
        $this->document->setId($id);

        if ($data)
        {
            if (isset($data->__created_at)) $this->document->setCreatedAt($data->__created_at);
            if (isset($data->__updated_at)) $this->document->setuUpdatedAt($data->__updated_at);

            foreach($data as $key => $value)
            {
                if (is_array($value)) $value = (object) $value;
                $this->document->{$key} = $value;
            }
        }

        return $this->document;
    }


    //--------------------------------------------------------------------


    /**
    * set
    *
    *
    */
    public function set($data)
    {
        if ($data)
        {
            foreach($data as $key => $value)
            {
                if (is_array($value)) $value = (object) $value;
                $this->document->{$key} = $value;
            }
        }

        return $this->document;
    }


    //--------------------------------------------------------------------


    /**
    * save
    *
    *
    */
    public function save(\Flatfile\Document $document)
    {
        $file_extension = $this->config->format::getFileExtension();
        $file_location  = $this->config->database.'/'.\Flatfile\Filesystem::validateName($document->getId()).'.'.$file_extension;

        $document->setUpdatedAt(time());

        if (!\Flatfile\Filesystem::read($file_location) || !$document->createdAt())
        {
            $document->setCreatedAt(time());
        }

        $data = $this->config->format::encode( $document->saveAs() );

        return \Flatfile\Filesystem::write($file_location,$data);
    }


    //--------------------------------------------------------------------


    /**
    * file
    *
    *
    */
    public function read($name)
    {
        $file_extension = $this->config->format::getFileExtension();
        $file_location  = $this->config->database.'/'.\Flatfile\Filesystem::validateName($name).'.'.$file_extension;

        return $this->config->format::decode( \Flatfile\Filesystem::read($file_location) );
    }


    //--------------------------------------------------------------------


    /**
    * delete
    *
    *
    */
    public function delete(\Flatfile\Document $document)
    {
        $file_extension = $this->config->format::getFileExtension();
        $file_location  = $this->config->database.'/'.\Flatfile\Filesystem::validateName($document->getId()).'.'.$file_extension;

        return \Flatfile\Filesystem::delete($file_location);
    }


    //--------------------------------------------------------------------


    /**
    * config
    *
    * static
    *
    */
    public static function config(array $options)
    {
        return new \Flatfile\Config($options);
    }


    //--------------------------------------------------------------------


    /**
    * toArray
    *
    */
    public function toArray(\Flatfile\Document $document)
    {
        $vars = get_object_vars($document);
        return $this->objectToArray($vars);
    }


    //--------------------------------------------------------------------


    /**
    * arrayToObject
    *
    */
    public function arrayToObject($arr)
    {
        if (!is_object($arr) && !is_array($arr))
        {
            return $arr;
        }

        $arr = (object) $arr;

        foreach($arr as $key => $value)
        {
            $arr->{$key} = $this->arrayToObject($value);
        }

        return $arr;
    }


    //--------------------------------------------------------------------


    /**
    * objectToArray
    *
    */
    public function objectToArray($obj)
    {
        if (!is_object($obj) && !is_array($obj))
        {
            return $obj;
        }

        $arr = [];
        foreach ($obj as $key => $value)
        {
            $arr[$key] = $this->objectToArray($value);
        }

        return $arr;
    }

}
