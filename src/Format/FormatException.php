<?php namespace Filebase\Format;

class FormatException extends \Exception
{
    private $inputData;

    public function __construct($message, $code = 0, \Exception $previous = null, $inputData = null)
    {
        parent::__construct($message, $code, $previous);
        $this->inputData = $inputData;
    }

    public function getInputData()
    {
        return $this->inputData;
    }
}

