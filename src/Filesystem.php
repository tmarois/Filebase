<?php  namespace Filebase;


class Filesystem
{

    /**
     * read
     *
     *
     */
    public static function read($path)
    {
        if(!file_exists($path))
        {
            return false;
        }

        $file = fopen($path, 'r');
        $contents = fread($file, filesize($path));
        fclose($file);

        return $contents;
    }



    //--------------------------------------------------------------------


    /**
     * Writes data to the filesystem.
     *
     * @param  string $path     The absolute file path to write to
     * @param  string $contents The contents of the file to write
     *
     * @return boolean          Returns true if write was successful, false if not.
     */
    public static function write($path, $contents)
    {
        $fp = fopen($path, 'w+');

        if(!flock($fp, LOCK_EX))
        {
            return false;
        }

        $result = fwrite($fp, $contents);

        flock($fp, LOCK_UN);
        fclose($fp);

        return $result !== false;
    }


    //--------------------------------------------------------------------


    /**
     * delete
     *
     * @param string $path
     *
     * @return boolean True if deleted, false if not.
     */
    public static function delete($path)
    {
        return unlink($path);
    }


    //--------------------------------------------------------------------


    /**
     * Validates the name of the file to ensure it can be stored in the
     * filesystem.
     *
     * @param string $name The name to validate against
     *
     * @return bool Returns true if valid. Throws an exception if not.
     */
    public static function validateName($name)
    {
        if (!preg_match('/^[0-9A-Za-z\_\-]{1,63}$/', $name))
        {
            throw new \Exception(sprintf('`%s` is not a valid file name.', $name));
        }

        return true;
    }


    //--------------------------------------------------------------------


    /**
     * Get an array containing the path of all files in this repository
     *
     * @return array An array, item is a file
     */
    public static function getAllFiles($path = '',$ext = 'json')
    {
        $files = [];
        $_files = glob($path.'*.'.$ext);
        foreach($_files as $file)
        {
            $files[] = str_replace('.'.$ext,'',basename($file));
        }

        return $files;
    }


    //--------------------------------------------------------------------

}
