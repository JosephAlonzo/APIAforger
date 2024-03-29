<?php
namespace Models;

class Tags extends Base
{
    public string $name;
    public string $description;
    public ExternalDocumentation $externalDocs;

    function __construct()
    {
        $this->name = '';
        $this->description = '';
        $this->externalDocs = new ExternalDocumentation();
        $this->modelProperties = ["name", "externalDocs"];
        $this->requiredProperties = ["name"];
    }

    protected function isValidExternalDocs(){
        if( !$this->externalDocs->isValid() )
            return ["success"=> false, "message" => "externalDocs object is not valid" ];
        return ["success" => true];
    }
}