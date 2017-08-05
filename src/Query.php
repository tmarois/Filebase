<?php  namespace Filebase;


class Query extends QueryLogic
{

    protected $limit   = 0;
    protected $offset  = 0;
    protected $sortBy  = 'ASC';
    protected $orderBy = '';


    /**
    * $documents
    *
    */
    protected $documents = [];


    //--------------------------------------------------------------------


    /**
    * ->where()
    *
    */
    public function where(...$arg)
    {
        $this->addPredicate('and', $arg);

        return $this;
    }


    //--------------------------------------------------------------------


    /**
    * ->andWhere()
    *
    */
    public function andWhere(...$arg)
    {
        $this->addPredicate('and', $arg);

        return $this;
    }


    //--------------------------------------------------------------------


    /**
    * ->orWhere()
    *
    */
    public function orWhere(...$arg)
    {
        $this->addPredicate('or', $arg);

        return $this;
    }


    //--------------------------------------------------------------------


    /**
    * ->limit()
    *
    */
    public function limit($limit, $offset = 0)
    {
        $this->limit   = (int) $limit;

        if ($this->limit === 0)
        {
            $this->limit = 9999999;
        }

        $this->offset  = (int) $offset;

        return $this;
    }


    //--------------------------------------------------------------------


    /**
    * ->orderBy()
    *
    */
    public function orderBy(string $field, string $sort)
    {
        $this->orderBy = $field;
        $this->sortBy  = $sort;

        return $this;
    }


    //--------------------------------------------------------------------


    /**
    * addPredicate
    *
    */
    protected function addPredicate($logic,$arg)
    {
        $this->predicate->add($logic, $arg);
    }


    //--------------------------------------------------------------------


    /**
    * ->getDocuments()
    *
    */
    public function getDocuments()
    {
        return $this->documents;
    }


    //--------------------------------------------------------------------


    /**
    * ->results()
    *
    */
    public function results()
    {
        return parent::run()->toArray();
    }


    //--------------------------------------------------------------------


    /**
    * ->resultDocuments()
    *
    */
    public function resultDocuments()
    {
        return parent::run()->getDocuments();
    }


    //--------------------------------------------------------------------


    /**
    * ->first()
    *
    */
    public function first()
    {
        $results = parent::run()->toArray();
        return current($results);
    }


    //--------------------------------------------------------------------



    /**
    * toArray
    *
    * @param \Filebase\Document
    * @return array
    */
    public function toArray()
    {
        $docs = [];

        if (!empty($this->documents))
        {
            foreach($this->documents as $document)
            {
                $docs[] = (array) $document->getData();
            }
        }

        return $docs;
    }


    //--------------------------------------------------------------------

}
