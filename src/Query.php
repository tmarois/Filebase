<?php 
namespace Filebase;

use Filebase\Table;
use Filebase\Support\Filesystem;
use Filebase\Format\Json;
class Query 
{
    public $table;
    public $fs;
    public $formater;

    public function __construct(Table $table)
    {
        $this->table=$table;
        $this->fs=new Filesystem($table->fullPath());
        $this->formater = new Json();
        
    }
    public function getTable()
    {
        return $this->table;
    }
    public function getDatabase()
    {
        return $this->table->db();
    }

    public function create(array $args)
    {
        // TODO:ADD START POINT FOR ID 
        // TODO:VALIDATE
        $this->fs->write($this->table->genUniqFileId(0,'.json'),$this->formater->encode($args,true));  
    }
    public function find($id)
    {
        // TODO:set ext dina
        if($this->fs->has($id.'.json'))
        {
            return new Document($this->table,(array)json_decode($this->fs->read($id.'.json'),true));
        }
    }

}