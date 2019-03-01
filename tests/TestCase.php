<?php 
namespace Filebase\Test;

use PHPUnit\Framework\TestCase as base_case;

class TestCase extends base_case
{
    public $path=__DIR__.'/tmp/';

    protected function setUp():void
    {
        @mkdir($this->path);
    }

    protected function tearDown():void
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