<?php  namespace Filebase;


class Validate
{

    /**
     * valid
     *
     * @param object document $object
     * @return bool (true/false)
     */
    public static function valid(Document $object)
    {
        $document = $object->toArray();

        self::validateLoop($document,$object,self::getValidateRules($object));

        return true;
    }


    //--------------------------------------------------------------------


    /**
    * getValidateRules
    *
    * @return database->config
    */
    public static function getValidateRules(Document $object)
    {
        return $object->getDatabase()->getConfig()->validate;
    }


    //--------------------------------------------------------------------


    /**
    * validateLoop
    */
    public static function validateLoop($document,$object,$rules)
    {
        foreach($rules as $key => $rule)
        {
            if (!isset($rule['type'],$rule['required']) && isset($document[$key]))
            {
                self::validateLoop($document[$key],$object,$rules[$key]);

                continue;
            }

            self::validateRules($document,$key,$rules[$key],$object);
        }
    }


    /**
    * validateRules
    */
    public static function validateRules($document,$key,$rules,$object)
    {
        // checks variable type
        if (isset($document[$key],$rules['type']))
        {
            if (!self::checkType($document[$key],$rules['type']))
            {
                throw new \Exception('Validation Failed setting variable on '.$object->getId().' - ['.$key.'] does not match type '.$rules['type']);
            }
        }

        // check if variable is required
        if (isset($rules['required']) && $rules['required']===true)
        {
            if (!isset($document[$key]))
            {
                throw new \Exception('Validation Failed setting variable on '.$object->getId().' - ['.$key.'] is required');
            }
        }

        return $object;
    }


    //--------------------------------------------------------------------


    /**
    * checkType
    *
    */
    private static function checkType($variable, $type)
    {
        switch($type)
        {
            case 'string':
            case 'str':
                if (is_string($variable))
                {
                    return true;
                }

                break;

            case 'integer':
            case 'int':
                if (is_integer($variable))
                {
                    return true;
                }

                break;

            case 'array':
            case 'arr':
                if (is_array($variable))
                {
                    return true;
                }

                break;

            default:
                return true;
        }

        return false;
    }


    //--------------------------------------------------------------------


    /**
    * hasCustomFilter
    *
    * CURRENTLY NOT USED
    */
    public static function hasCustomFilter(Document $object,$rules = false,$search = [])
    {
        if ($rules === false)
        {
            $rules = $object->getDatabase()->getConfig()->validate;
        }

        foreach($rules as $k => $rule)
        {
            if (isset($rule['custom_filter'],$rule['type']) && $rule['type'] == 'array')
            {
                $search[$k] = $rule['custom_filter'];
            }

            if (is_array($k))
            {
                $search[$k] = self::hasCustomFilter($object,$k,$search);
            }
        }

        return $search;
    }


}
