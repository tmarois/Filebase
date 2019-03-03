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

    /**
     * create fake items in table
     */
    public function fakeRecordCreator($limit=5)
    {
        $a=0;
        $tbl=$this->tmp_db->table('tbl_name');
        while($a < $limit)
        {
			$a++;
			
			$status = 'enabled';

			if ($a > 5) {
				$status = 'disabled';
			}

			if ($a > 8) {
				$status = 'pending';
			}

        
            $tbl->query()->create([
                'Foo'=>'bar'.$a,
                'name'=>'name'.$a,
                'status' => $status
            ]);
        }

        return $tbl;
    }
    
}