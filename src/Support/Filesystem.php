<?php namespace Filebase\Support;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as FS;
use League\Flysystem\AdapterInterface;

/**
 * The filesystem controls the files
 * and diretories of Filebase
 * 
 * Filesystem uses 
 * @see https://flysystem.thephpleague.com/docs/
 * 
 */
class Filesystem
{

    /**
     * Filesystem property
     * 
     * @var League\Flysystem\Filesystem
     */
    public $filesystem;

    /**
     * Filesystem starter
     * 
     * @param string $path
     */
    public function __construct($adapter)
    {
        if(is_object($adapter) && (($adapter=$adapter()) instanceof AdapterInterface))
        {
            $this->filesystem = new FS($adapter);
        }else{
            // by defult will set local path
            $this->filesystem = new FS(new Local($adapter));
        }

    }

    /**
     * Retunrs the filesystem property
     * 
     * @param League\Flysystem\Filesystem
     */
    public function getFS()
    {
        return $this->filesystem;
    }

    /**
     * Read a specific file
     * 
     * @param string $path
     */
    public function read($path)
    {
        return $this->filesystem->read($path);
    }

    /**
     * Check if a file exist
     * 
     * @param string $path
     * @return boolean
     */
    public function has($path)
    {
        return $this->filesystem->has($path);
    }

    /**
     * Create a file if one doesnt
     * exists
     * 
     * @param string $path
     * @param string $data
     * 
     * @return string
     */
    public function write($path, $data = '')
    {
        return $this->filesystem->write($path, $data);
    }

    /**
     * Update an existing a file
     * 
     * @param string $path
     * @param string $data
     * 
     * @return string
     */
    public function update($path, $data = '')
    {
        return $this->filesystem->update($path, $data);
    }

    /**
     * Write to a file or create
     * one if it doesnt exist
     * 
     * @param string $path
     * @param string $data
     * 
     * @return string
     */
    public function put($path, $data = '')
    {
        return $this->filesystem->put($path, $data);
    }

    /**
     * delete spceific file
     * 
     * @param string $path
     */
    public function delete($path)
    {
        return $this->filesystem->delete($path);
    }

    /**
     * Get all folders within directory
     * 
     * @param string $path
     */
    public function folders($path = '')
    {
        $items = $this->filesystem->listContents($path);

        $folder = [];
        foreach($items as $item) {
            if ($item['type']=='dir') {
                $folder[] = $item['basename'];
            }
        }

        return $folder;
    }

    /**
     * Get all files within directory
     * 
     * This will ONLY get the files that have our "EXTENSION"
     * For example ".json" (leave $ext blank and it will get all files)
     * 
     * @param string $path
     * @param string $ext
     */
    public function files($path = '', $ext)
    {
        $items = $this->filesystem->listContents($path);

        $files = [];
        foreach($items as $item) {
            if ($item['type']=='file' && ($ext && ($item['extension']??'')==$ext)) {
                $files[] = $item['filename'];
            }
        }

        return $files;
    }

    /**
     * Create Directory
     * 
     * @param string $path
     */
    public function mkdir($path)
    {
        return $this->filesystem->createDir($path);
    }

    /**
     * Remove Directory (deletes directory and its contents)
     * 
     * @param string $path
     */
    public function rmdir($path)
    {
        return $this->filesystem->deleteDir($path);
    }

}
