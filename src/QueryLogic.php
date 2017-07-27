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


    //--------------------------------------------------------------------


    /**
    * __construct
    *
    */
    public function __construct(Database $database)
    {
        $this->database  = $database;
        $this->predicate = new Predicate();
    }


    //--------------------------------------------------------------------


    /**
    * run
    *
    */
    public function run()
    {
        $documents = $this->database->findAll(true,false);

        if ($predicates = $this->predicate->get())
        {
            $documents = $this->filter($documents, $predicates);
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
