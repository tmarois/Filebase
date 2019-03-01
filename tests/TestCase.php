<?php 
namespace Filebase\Test;

use PHPUnit\Framework\TestCase as base_case;
use Filebase\Database;

class TestCase extends base_case
{
    public $path=__DIR__.'/tmp/';
    public $tmp_db;

    protected function setUp():void
    {
        @mkdir($this->path);
        $this->tmp_db=new Database([
          'path'=>$this->path
        ]);

    }
    
    protected function tearDown():void
    {
        $this->rrmdir($this->path);
        $this->rrmdir(__DIR__."/database/");
        $this->rrmdir(__DIR__."/database1/");
    }

    /**
     * remove folder with sub items after each test 
     */
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