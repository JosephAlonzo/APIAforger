<?php
namespace Models;


class Server extends Base
{
    /**
     * REQUIRED. A URL to the target host. This URL supports Server Variables and MAY be relative, 
     * to indicate that the host location is relative to the location where the OpenAPI document is being served. 
     * Variable substitutions will be made when a variable is named in {brackets}.
     */
    public string $url;
    /**
     * An optional string describing the host designated by the URL. CommonMark syntax MAY be used for rich text representation.
     */
    public string $description;
    /**
     * A map between a variable name and its value. The value is used for substitution in the server's URL template.
     * Map[string, Server Variable Object]
     */
    public array $variables;

    function __construct(?string $url='')
    {
        $this->url = $url;
        $this->initialize();
        $this->modelProperties = [ "url" , "variables"];
        $this->requiredProperties = [ "url" ];
    }

    private function initialize(){
        $this->description = '';
        $this->variables = [];
    }

    public function setVariables($variables){
        $instance = new ServerVariable();
        $instance = $this->setInstanceOfObject($instance, $variables);
        array_push($this->servers, $instance);
    }

    protected function isValidUrl(){
        if ( $this->isValidURLFormat($this->url) )
            return [ "success" => false, "message" => "url MUST be a url valid format" ];
        return ["success" => true ];
    }

    protected function isValidVariables(){
        if( !$this->is_associative_array($this->variables) ) 
            return [ "success" => false, "message" => "variables MUST be an associative array" ];

        foreach ($this->variables as $index => $value) {
            if( !($value instanceof ServerVariable) )
                return ["success" => false, "message" => "variable with index $index is not an instance of ServerVariable"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "ServerVariable is not passing the validation, index: $index"];
        }
        return ["success" => true];
    }
}

