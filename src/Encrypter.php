<?php  
namespace Filebase;
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\File;

class Encrypter
{
    private $ext;
    private $secretKey;
    private $masterKey;
    private $folder;

    public function __construct(String $keyStoragePath, String $keyName = 'master_key', String $ext = 'key')
    {
        if (!extension_loaded('sodium')) {
            throw new \Throwable('Sorry! you cannot use encryption because sodium is not installed in your application.');
        }
       
        $this->ext = '.'.$ext;
        $this->folder = $keyStoragePath.'/keys';
        if(!is_dir($this->folder)){
            mkdir($this->folder, 0750, true);
        }
        $this->folder = $this->folder.'/'.$keyName.$this->ext;
        if (!file_exists($this->folder)) {
            KeyFactory::save(KeyFactory::generateEncryptionKey(), $this->folder);
            chmod($this->folder, 0444);
        }
        $this->secretKey = KeyFactory::loadEncryptionKey($this->folder);
    }

    public function encryptFile($fileInput, $fileOutput)
    {
        try {
            return File::encrypt($fileInput, $fileOutput, $this->secretKey);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function decryptFile($fileInput, $fileOutput)
    {
        try {
            return File::decrypt($fileInput, $fileOutput, $this->secretKey);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}