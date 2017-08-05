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
        if (count($arg) == 3)
        {
            $this->predicate->add($logic, $arg);
        }

        if (count($arg) == 1)
        {
            if (isset($arg[0]) && count($arg[0]))
            {
                foreach($arg[0] as $key => $value)
                {
                    if ($value == '') continue;

                    $this->predicate->add($logic, $this->formatWhere($key, $value));
                }
            }
        }
    }


    //--------------------------------------------------------------------


    /**
    * formatWhere
    *
    */
    protected function formatWhere($key, $value)
    {
        return [$key,'==',$value];
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
