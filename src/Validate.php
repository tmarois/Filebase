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

        $rules = self::getValidateRules($object);
        foreach($rules as $k => $rule)
        {

            // checks variable type
            if (isset($document[$k],$rule['type']))
            {
                if (!self::checkType($document[$k],$rule['type']))
                {
                    throw new \Exception('Validation Failed setting variable on '.$object->getId().' - ['.$k.'] does not match type '.$rule['type']);
                }
            }

            // check if variable is required
            if (isset($rule['required']) && $rule['required']===true)
            {
                if (!isset($document[$k]))
                {
                    throw new \Exception('Validation Failed setting variable on '.$object->getId().' - ['.$k.'] is required');
                }
            }


            // set the default (if is not set)
            if (isset($rule['default']))
            {
                if (!isset($document[$k]))
                {
                    $object->{$k} = $rule['default'];
                }
            }

        }

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
    * checkType
    *
    */
    private static function checkType($variable, $type)
    {
        switch($type)
        {
            case 'string': case 'str':
                if (is_string($variable)) return true;
                break;

            case 'integer': case 'int':
                if (is_integer($variable)) return true;
                break;

            case 'array': case 'arr':
                if (is_array($variable)) return true;
                break;

            default:
                return true;
        }

        return false;
    }


}
