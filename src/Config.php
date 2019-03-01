<?php namespace Filebase;

use Exception;
use Filebase\Format\Json;

/**
 * The config class
 *
 * Used for setting up our main database
 * configuration
 *
 */
class Config
{
    /**
    * The path of the database directory
    * The default is set to your current location
    * plus /database/~
    *
    * @var string
    */
    protected $path = __DIR__.'/database';

    /**
    * The current format class
    * The default is set to JSON
    *
    * @var Filebase\Format\FormatInterface
    */
    protected $format = Json::class;

    /**
    * The file extension
    * The default is set to json
    *
    * @var string
    */
    protected $extension = 'json';

    /**
    * This will set the database to read-only mode
    * No changes can be made to the database
    *
    * default false
    *
    * @var boolean
    */
    protected $readonly = false;

    /**
    * The config starting point, load in the necessary config array
    *
    * @param array $config
    */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }
    }

    /**
    * get property (it's MAGIC!)
    *
    * @param string $name
    * @return mixed
    */
    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        return null;
    }
}
