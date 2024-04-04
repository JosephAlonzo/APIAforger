<?php
namespace Models;

//What is a property?  whe will user the properties into a body request to force the user to make a good request, this class extends from schema definition 
//Why not use only 'Schema' class instead? To make it easier to read
class Property extends Schema
{
    public bool $requiredProperty;
    //name will be used to define the property and after that it will be removed from the schema definition
    public string $name;

    public function __construct($name) {
        parent::__construct();
        $this->requiredProperty = false;
        $this->name = $name;
    }
}
