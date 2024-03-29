<?php 
namespace Models;

class ServerVariable extends Base
{
    /**
     * An enumeration of string values to be used if the substitution options are from a limited set. The array SHOULD NOT be empty.
     */
    public array $enum;
    /**
     * REQUIRED. The default value to use for substitution, which SHALL be sent if an alternate value is not supplied.
     * Note this behavior is different than the Schema Object's treatment of default values, because in those cases parameter values are optional. 
     * If the enum is defined, the value SHOULD exist in the enum's values.
     */
    public string $default;
    /**
     * An optional description for the server variable. CommonMark syntax MAY be used for rich text representation.
     */
    public string $description; 

    function __construct(?string $default = null)
    {
        $this->default = $default;
        $this->modelProperties = [ "default", "enum" ];
        $this->requiredProperties = [ "default" ];
    }

    protected function isValidEnum(){
        if(!count($this->enum))
            return ["success" => false, "message"=> "Enum SHOULD NOT be empty"];
        return ["success" =>true];
    }
}