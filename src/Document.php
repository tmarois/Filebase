<?php  namespace Filebase;

use Exception;
use Filebase\Database;
use Base\Support\Collection;
use Base\Support\Filesystem;

class Document
{

    /**
    * $database
    *
    * @var Filebase\Database
    */
    protected $db;


    /**
    * $name
    *
    * @var string
    */
    protected $name;


    /**
    * $path
    *
    * @var string
    */
    protected $path;


    /**
    * $collection
    *
    * @var Base\Support\Collection
    */
    protected $collection;


    /**
    * __construct
    *
    * Sets the database property
    *
    * @param Filebase\Database $database
    * @param string $name
    */
    public function __construct(Database $database, $name = '')
    {
        $this->db = $database;

        $this->name = $name;

        // make a safe file name
        $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '', $name);

        // the path of this document
        $this->path = $this->db->config()->path.'/'.$safeName.'.'.$this->db->config()->ext;

        $this->collection = $this->load($this->path);
    }


    /**
    * getName
    *
    * @return string $name
    */
    public function getName()
    {
        return $this->name;
    }


    /**
    * getPath
    *
    * @return string $path
    */
    public function getPath()
    {
        return $this->path;
    }


    /**
    * Load
    *
    * @param string $name
    * @return Base\Support\Collection
    */
    protected function load($path)
    {
        $contents = Filesystem::get($path) ?? '';

        $format = $this->db->config()->format;

        $data = (array) $format::decode( $contents ) ?? [];

        return (new Collection($data));
    }


    /**
    * save
    *
    * @return Base\Support\Filesystem
    */
    public function save()
    {
        if ($this->db->config()->readOnly === true)
        {
            throw new Exception("Filebase: This database is set to be read-only. No modifications can be made.");
        }

        $format = $this->db->config()->format;

        $data = $format::encode($this->collection->toArray(), $this->db->config()->prettyFormat);

        return Filesystem::put($this->path, $data);
    }


    /**
    * delete
    *
    * @return void
    */
    public function delete()
    {
        if ($this->db->config()->readOnly === true)
        {
            throw new Exception("Filebase: This database is set to be read-only. No modifications can be made.");
        }

        $this->collection = new Collection([]);

        Filesystem::delete($this->path);
    }


    /**
    * Call a method on the collection
    *
    * @param string $name
    * @param array $arguments
    * @return Base\Support\Collection mixed
    */
    public function __call($name, $arguments)
    {
        if (method_exists(Collection::class, $name))
        {
            return $this->collection->$name(...$arguments);
        }

        throw new Exception('Filebase: method "'.$name.'" does not exist.');
    }


    /**
    * get property from the collection
    *
    * @param string $name
    * @param mixed $default
    * @return Base\Support\Collection get
    */
    public function __get($name)
    {
        return $this->collection->get($name);
    }


    /**
    * set a new property into the collection
    *
    * @param string $name
    * @param mixed $default
    * @return Base\Support\Collection set
    */
    public function __set($name, $value)
    {
        return $this->collection->set($name, $value);
    }


    /**
     * Get the Document as ARRAY.
     *
     * @param  int  $options
     * @return string
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }


    /**
     * Get the Document as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson()
    {
        return $this->collection->toJson();
    }


    /**
    * If the document is being output as a string
    *
    * @return string JSON formatted
    */
    public function __toString()
    {
        return $this->toJson();
    }

}
