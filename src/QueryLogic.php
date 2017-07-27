<?php  namespace Filebase;


class QueryLogic
{

    /**
    * $database
    *
    * \Filebase\Database
    */
    protected $database;


    /**
    * $predicate
    *
    * \Filebase\Predicate
    */
    protected $predicate;


    /**
    * $cache
    *
    * \Filebase\Cache
    */
    protected $cache = false;


    //--------------------------------------------------------------------


    /**
    * __construct
    *
    */
    public function __construct(Database $database)
    {
        $this->database  = $database;
        $this->predicate = new Predicate();

        if ($this->database->getConfig()->cache===true)
        {
            $this->cache = new Cache($this->database);
        }
    }


    //--------------------------------------------------------------------


    /**
    * run
    *
    */
    public function run()
    {
        $predicates = $this->predicate->get();
        $documents  = [];

        if ($predicates && !empty($predicates))
        {
            if ($this->cache !== false)
            {
                $this->cache->setKey(json_encode($predicates));

                if ($cached_documents = $this->cache->get())
                {
                    return $cached_documents;
                }
            }

            $documents = $this->database->findAll(true,false);
            $documents = $this->filter($documents, $predicates);

            if ($this->cache !== false)
            {
                if ($cached_documents === false)
                {
                    $dsave = [];
                    foreach($documents as $document)
                    {
                        $dsave[] = $document->getId();
                    }

                    $this->cache->store($dsave);
                }
            }
        }

        return $documents;
    }


    //--------------------------------------------------------------------


    /**
    * filter
    *
    */
    protected function filter($documents, $predicates)
    {
        $results = [];

        if (isset($predicates['and']) && !empty($predicates['and']))
        {
            foreach($predicates['and'] as $predicate)
            {
                list($field, $operator, $value) = $predicate;

                $documents = array_values(array_filter($documents, function ($document) use ($field, $operator, $value) {
                    return $this->match($document, $field, $operator, $value);
                }));

                $results = $documents;
            }
        }

        if (isset($predicates['or']) && !empty($predicates['or']))
        {
            foreach($predicates['or'] as $predicate)
            {
                list($field, $operator, $value) = $predicate;

                $documents = array_values(array_filter($documents, function ($document) use ($field, $operator, $value) {
                    return $this->match($document, $field, $operator, $value);
                }));


                $results = array_unique(array_merge($results, $documents), SORT_REGULAR);
            }
        }

        return $results;
    }


    //--------------------------------------------------------------------


    /**
    * matchDocuments
    *
    */
    public function matchDocuments($documents, $field, $operator, $value)
    {
        $docs = [];

        foreach($documents as $document)
        {
            if ($this->match($document, $field, $operator, $value)===true)
            {
                $docs[] = $document;
            }
        }

        return $docs;
    }


    //--------------------------------------------------------------------


    /**
    * match
    *
    */
    public function match($document, $field, $operator, $value)
    {
        $d_value = $document->field($field);

        switch (true)
        {
            case ($operator === '=' && $d_value == $value):
                return true;
            case ($operator === '==' && $d_value == $value):
                return true;
            case ($operator === '===' && $d_value === $value):
                return true;
            case ($operator === '!=' && $d_value != $value):
                return true;
            case ($operator === '!==' && $d_value !== $value):
                return true;
            case ($operator === '>'  && $d_value >  $value):
                return true;
            case ($operator === '>=' && $d_value >= $value):
                return true;
            case ($operator === '<'  && $d_value <  $value):
                return true;
            case ($operator === '>=' && $d_value >= $value):
                return true;
            case ($operator === 'IN' && in_array($d_value, (array) $value)):
                return true;
            case ($operator === 'IN' && in_array($value, (array) $d_value)):
                return true;
            default:
                return false;
        }

    }


    //--------------------------------------------------------------------


}
