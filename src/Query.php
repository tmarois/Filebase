<?php  namespace Filebase;


class Query extends QueryLogic
{

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
    * add
    *
    */
    protected function addPredicate($logic,$arg)
    {
        if (count($arg) == 3)
        {
            $this->predicate->add($logic,$arg);
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
    protected function formatWhere($key,$value)
    {
        return [$key,'==',$value];
    }


    //--------------------------------------------------------------------


    /**
    * ->results()
    *
    */
    public function results($returnArray = true)
    {
        return parent::run($returnArray);
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
