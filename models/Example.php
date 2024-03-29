<?php
namespace Models;


class Example extends Base
{
    /**
     * Short description for the example.
     */
    public string $summary;
    /**
     * Long description for the example. CommonMark syntax MAY be used for rich text representation.
     */
    public string $description;
    /**
     * Embedded literal example. The value field and externalValue field are mutually exclusive. 
     * To represent examples of media types that cannot naturally represented in JSON or YAML, use a string value to contain the example, escaping where necessary.
     */
    public mixed $value;
    /**
     * A URL that points to the literal example. 
     * This provides the capability to reference examples that cannot easily be included in JSON or YAML documents. 
     * The value field and externalValue field are mutually exclusive.
     */
    public string $externalValue;

    function __construct()
    {
        $this->initialize();
    }

    private function initialize(){
        $this->summary = '';
        $this->description = '';
        $this->value = '';
    }

} 