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
        $this->fs=new Filesystem($table->fullPath()."/");
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

    public function create($args)
    {
        $this->fs->write($this->table->genUniqFileId(0,'.json'),$this->formater->encode($args,true));
    }

}