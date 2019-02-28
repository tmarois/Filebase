<?php 
namespace Filebase;

use Filebase\Table;

class Query 
{
    public $table;

    public function __construct(Table $table)
    {
        $this->table=$table;
    }
    public function getTable()
    {
        return $this->table;
    }
    public function getDatabase()
    {
        return $this->table->db();
    }
}