<?php 
namespace Models;


class Discriminator extends Base
{   
    /*
    Exemple
    propertyName: petType
    mapping:
      dog: '#/components/schemas/Dog'
      monster: 'https://gigantic-server.com/schemas/Monster/schema.json'
    */
    /**
     * REQUIRED. The name of the property in the payload that will hold the discriminator value.
     */
    public string $propertyName;
    /**
     * An object to hold mappings between payload values and schema names or references.
     * Map[string, string]
     */
    public array $mapping;
    
    function __construct(string $propertyName = ''){
      $this->propertyName = $propertyName;
      $this->initialize();
      $this->modelProperties = ["propertyName", "mapping"];
      $this->requiredProperties = ["propertyName"];
    }

    private function initialize(){
      $this->mapping = [];
    }

    protected function isValidMapping(){
      if( !$this->is_associative_array($this->mapping) ) 
        return [ "success" => false, "message" => "mapping MUST be an associative array" ];
      return ["success" => true];
    }
}

// $media = new Discriminator();
// $mapping =[
//   "cat" => "test", "cat" => "test"
// ];
// $media->mapping = $mapping;
// $media->isValid();
// var_dump($media->errors);