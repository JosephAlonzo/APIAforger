<?php 
namespace Models;

class Reference extends Base 
{
    public string $ref;
    // Examples
    // { "$ref": "#/components/schemas/Pet" }
    // { "$ref": "Pet.json" }
    // { "$ref": "definitions.json#/Pet" }
    public function __construct(?string $ref = ''){
        $this->ref = $ref;
        $this->modelProperties = ["ref"];
        $this->requiredProperties = ["ref"];
    }
}
