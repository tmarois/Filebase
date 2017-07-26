<?php  namespace Filebase;


class Database
{

    /**
    * $config
    *
    * Stores all the configuration object settings
    * \Filebase\Config
    */
    protected $config;


    //--------------------------------------------------------------------


    /**
    * __construct
    *
    */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        // Check directory and create it if it doesn't exist
        if (!is_dir($this->config->dir))
        {
            if (!@mkdir($this->config->dir, 0777, true))
            {
                throw new \Exception(sprintf('`%s` doesn\'t exist and can\'t be created.', $this->config->dir));
            }
        }
        else if (!is_writable($this->config->dir))
        {
            throw new \Exception(sprintf('`%s` is not writable.', $this->config->dir));
        }
    }


    //--------------------------------------------------------------------


    /**
    * findAll()
    *
    * Finds all documents in database directory.
    * Then returns you a list of those documents.
    *
    * @param bool $include_documents (include all document objects in array)
    *
    * @return array $items
    */
    public function findAll($include_documents = true)
    {
        $file_extension = $this->config->format::getFileExtension();
        $file_location  = $this->config->dir.'/';

        $items = Filesystem::getAllFiles($file_location,$file_extension);
        if ($include_documents==true)
        {
            $items = [];

            foreach($all as $a)
        	{
        		$items[] = $this->get($a);
        	}

            return $items;
        }

        return $items;
    }


    //--------------------------------------------------------------------


    /**
    * get
    *
    * retrieves a single result (file)
    *
    * @param mixed $id
    *
    * @return $document \Filebase\Document object
    */
    public function get($id)
    {
        $content = $this->read($id);

        $document = new Document($this);
        $document->setId($id);

        if ($content)
        {
            if (isset($content['__created_at'])) $document->setCreatedAt($content['__created_at']);
            if (isset($content['__updated_at'])) $document->setUpdatedAt($content['__updated_at']);

            $this->set($document,(isset($content['data']) ? $content['data'] : []));
        }

        return $document;
    }


    //--------------------------------------------------------------------


    /**
    * set
    *
    * @param $document \Filebase\Document object
    * @param mixed $data should be an array
    *
    * @return $document \Filebase\Document object
    */
    public function set(Document $document, $data)
    {
        if ($data)
        {
            foreach($data as $key => $value)
            {
                if (is_array($value)) $value = (array) $value;
                $document->{$key} = $value;
            }
        }

        return $document;
    }


    //--------------------------------------------------------------------


    /**
    * save
    *
    * @param $document \Filebase\Document object
    * @param mixed $data should be an array, new data to replace all existing data within
    *
    * @return (bool) true or false if file was saved
    */
    public function save(Document $document, $wdata = '')
    {
        $id             = $document->getId();
        $file_extension = $this->config->format::getFileExtension();
        $file_location  = $this->config->dir.'/'.Filesystem::validateName($id).'.'.$file_extension;
        $created        = $document->createdAt(false);

        if (isset($wdata) && $wdata !== '')
        {
            $document = new Document( $this );
            $document->setId($id);
            $document->set($wdata);
            $document->setCreatedAt($created);
        }

        if (!Filesystem::read($file_location) || $created==false)
        {
            $document->setCreatedAt(time());
        }

        $document->setUpdatedAt(time());

        $data = $this->config->format::encode( $document->saveAs() );

        return Filesystem::write($file_location,$data);
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
        $file_location  = $this->config->dir.'/'.Filesystem::validateName($name).'.'.$file_extension;

        return $this->config->format::decode( Filesystem::read($file_location) );
    }


    //--------------------------------------------------------------------


    /**
    * delete
    *
    * @param $document \Filebase\Document object
    * @return (bool) true/false if file was deleted
    */
    public function delete(Document $document)
    {
        $file_extension = $this->config->format::getFileExtension();
        $file_location  = $this->config->dir.'/'.Filesystem::validateName($document->getId()).'.'.$file_extension;

        return Filesystem::delete($file_location);
    }


    //--------------------------------------------------------------------


    /**
    * toArray
    *
    */
    public function toArray(Document $document)
    {
        return $this->objectToArray( $document->getData() );
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


    //--------------------------------------------------------------------


    /**
    * getConfig
    *
    * @return $config
    */
    public function getConfig()
    {
        return $this->config;
    }

}
