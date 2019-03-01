<?php 
namespace Filebase\Test;

use PHPUnit\Framework\TestCase as base_case;

class TestCase extends base_case
{
    public $path=__DIR__.'/tmp/';

    public function setUp()
    {
        @mkdir($this->path);
    }
    
    public function tearDown()
    {
        $this->rrmdir($this->path);
    }

    public function rrmdir($dir) 
    { 
        if (is_dir($dir)) { 
          $objects = scandir($dir); 
          foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
              if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object); 
            } 
          } 
          reset($objects); 
          rmdir($dir); 
        } 
     } 
    
}