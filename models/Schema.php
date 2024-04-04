<?php
namespace Models;


class Schema extends JSONSchema
{
    public string $format = '';

    // see more information about https://swagger.io/docs/specification/data-models/oneof-anyof-allof-not/
    public array $allOf = []; //PHP 8.0 - string|Schema 
    public array $oneOf = []; //PHP 8.0 - string|Schema 
    public array $anyOf = []; //PHP 8.0 - string|Schema 
    public array $not = []; //PHP 8.0 - string|Schema 

    public $items = []; //PHP 8.0 - string|Schema 
    public $properties = []; //PHP 8.0 - string|Schema 
    public $additionalProperties = []; //PHP 8.0 - string|Schema 
    public string $description = '';
    /**
     * The default value represents what would be assumed by the consumer of the input as the value of the schema if one is not provided. Unlike JSON Schema, 
     * the value MUST conform to the defined type for the Schema Object defined at the same level. For example, if type is string, then default can be "foo" but cannot be 1.
     */
    public string $default= '';
    //a partir de aqui revisar de nuevo en la documentacion si tengo que agregar validaciones por ahora solo agregue validaciones de tipo de instancia
    /**
     * A true value adds "null" to the allowed type specified by the type keyword, only if type is explicitly defined within the same Schema Object. 
     * Other Schema Object constraints retain their defined behavior, and therefore may disallow the use of null as a value. 
     * A false value leaves the specified or default type unmodified. The default value is false.
     */
    public bool $nullable = false;
    /**
     * Adds support for polymorphism. The discriminator is an object name that is used to differentiate between other schemas which may satisfy the payload description. 
     * See Composition and Inheritance for more details.
     */
    public Discriminator $discriminator;   
    /**
     * Relevant only for Schema "properties" definitions. Declares the property as "read only". 
     * This means that it MAY be sent as part of a response but SHOULD NOT be sent as part of the request. 
     * If the property is marked as readOnly being true and is in the required list, the required will take effect on the response only. 
     * A property MUST NOT be marked as both readOnly and writeOnly being true. Default value is false.
     */
    public bool $readOnly = false;
    /**
     * Relevant only for Schema "properties" definitions. Declares the property as "write only". 
     * Therefore, it MAY be sent as part of a request but SHOULD NOT be sent as part of the response. 
     * If the property is marked as writeOnly being true and is in the required list, the required will take effect on the request only. 
     * A property MUST NOT be marked as both readOnly and writeOnly being true. Default value is false.
     */
    public bool $writeOnly = false;
    /**
     * This MAY be used only on properties schemas. It has no effect on root schemas. 
     * Adds additional metadata to describe the XML representation of this property.
     */
    public Xml $xml;
    /**
     * Additional external documentation for this schema.
     */
    public ExternalDocumentation $externalDocs;
    /**
     * A free-form property to include an example of an instance for this schema. 
     * To represent examples that cannot be naturally represented in JSON or YAML, 
     * a string value can be used to contain the example with escaping where necessary.
     */
    public string $example = '';
    /**
     * Specifies that a schema is deprecated and SHOULD be transitioned out of usage. Default value is false.
     */
    public bool $deprecated = false;

    protected array $acceptedTypes = [
        'integer' => ["formats" => ['int32', 'int64']],
        'number' =>  ["formats" => ['float', 'double']],
        'string' =>  ["formats" => ['byte', 'binary', 'date', 'date-time', "password"]],
        'boolean',
        "object",
        "array",
    ];

    function __construct(string $type = '', $format = '')
    {   
        parent::__construct();
        $this->type = $type;
        $this->format = !$format ? '': $format;
        $this->discriminator = new Discriminator;
        $this->xml = new Xml();
        $this->externalDocs = new ExternalDocumentation();
        array_push($this->defaultClassProperties, 'acceptedTypes');
    }
    
    function isValid(): bool
    {
        parent::isValid();
        $properties = [
            'format',
            'allOf',
            'anyOf',
            'not',
            'items',
            'properties',
            'additionalProperties',
            'description',
            'default',
            'nullable',
            'discriminator',
            'readOnly',
            'writeOnly',
            'xml',
            'externalDocs',
            'example',
            'deprecated'
        ];
        $required = ['items'];
        return parent::makeValidations($properties, $required);
    }

    public function setOneOf($schema, $key =null){
        $instance = new Schema(); 
        $instance = $this->setInstanceOfObject($instance, $schema);
        array_push($this->oneOf, $instance);
    }

    public function setProperties($schema, $key = ""){
        $className = get_class($schema);
        
        if ( $className == "Models\Property" ){
            if($schema->requiredProperty){
                array_push($this->required, $schema->name); 
            }
            $key = $schema->name;
            $schema->name = "";
        }
        
        $instance = new Schema(); 
        $instance = $this->setInstanceOfObject($instance, $schema);
        $this->properties[$key] = $instance;
    }

    /**
     * The value of this keyword MUST be either a string or an array. If it is an array, elements of the array MUST be strings and MUST be unique.
     * String values MUST be one of the six primitive types ("null", "boolean", "object", "array", "number", or "string"), or "integer" which matches any number with a zero fractional part.
     * An instance validates if and only if the instance is in any of the sets listed for this keyword.
     */
    protected function isValidType()
    {
        $typesKeys = array_keys($this->acceptedTypes);
        if (!in_array($this->type, $typesKeys))
            return ["success" => false, "message" => "The value {$this->type} is not an accepted type"];
        return ["success" => true];
    }

    /**
     * See Data Type Formats for further details. While relying on JSON Schema's defined formats, the OAS offers a few additional predefined formats.
     */
    protected function isValidFormat()
    {
        if (!$this->type) return ['success' => false, 'message' => 'type is not defined'];
        if (!$this->format) return ['success' => true, 'message' => 'default value will be added'];

        $type = $this->acceptedTypes[$this->type];
        $formats = $type["formats"];

        if (!$formats && $this->format) return ['success' => false, 'message' => 'format option is not required for this data-type'];
        foreach ($formats as $format) {
            if ($this->format == $format) {
                return ['success' => true, 'message' => 'correct format'];
            }
        }
        return ['success' => false, 'message' => 'format is not match with the accepted formats for this data type ' . $this->type];
    }


    /**
     * allOf – validates the value against all the subschemas
     */
    protected function isValidAllOf(): array
    {
        return $this->isValidCombineSchemaKeyword($this->not, "allOf");
    }
    /**
     * anyOf – validates the value against any (one or more) of the subschemas
     */
    protected function isValidAnyOf(): array
    {
        return $this->isValidCombineSchemaKeyword($this->not, "anyOf");
    }
    /**
     * oneOf – validates the value against exactly one of the subschemas
     */
    protected function isValidOneOf(): array
    {
        return $this->isValidCombineSchemaKeyword($this->not, "oneOf");
    }
    /**
     * not – keyword which you can use to make sure the value is not valid against the specified schema.
     */
    protected function isValidNot(): array
    {
        return $this->isValidCombineSchemaKeyword($this->not, "not");
    }

    protected function isValidItems(): array
    {
        if ($this->type == "array" && !$this->items) return ["success" => false, "message" => "items MUST be present if the type is array"];
        if (!$this->isSchemaOrReference($this->items))
            return ["success" => false, "message" => "items should be type Reference or valid Schema object"];
        return ["success" => true];
    }

    protected function isValidProperties(): array
    {
        foreach ($this->modelProperties as $value) {
            if (!$this->isSchemaOrReference($value))
                return ["success" => false, "message" => "all the properties should be type Reference or valid Schema object"];
        }
        return ["success" => true];
    }

    protected function isValidAditionalProperties(): array
    {
        if (!$this->isSchemaOrReference($this->additionalProperties))
            return ["success" => false, "message" => "aditionalProperties should be type Reference or valid Schema object"];
        return ["success" => true];
    }

    protected function isValidDescription(): array
    {
        if (is_string($this->description)) return ["success" => true];
        return ["success" => false, "message" => "Description SHOULD be type string"];
    }

    protected function isValidDefault(): array
    {
        return ["success" => true];
    }

    protected function isValidNullable(): array
    {
        if( !is_bool( $this->nullable) )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidDiscriminator(): array
    {
        if( !$this->discriminator instanceof Discriminator )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidReadOnly(): array
    {
        if( !is_bool( $this->readOnly) )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidWriteOnly(): array
    {
        if( !is_bool( $this->writeOnly) )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidXml(): array
    {
        if( !$this->xml instanceof Xml )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidExternalDocs(): array
    {
        if( !$this->externalDocs instanceof ExternalDocumentation )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidExample(): array
    {
        if( !is_string($this->example) )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidDeprecated(): array
    {
        if( !is_bool( $this->deprecated) )
            return ["success" => false];
        return ["success" => true];
    }

    protected function isValidCombineSchemaKeyword($currentKeywordValue, $keyword): array
    {
        foreach ($currentKeywordValue as $value) {
            if (!$this->isSchemaOrReference($value))
                return ["success" => false, "message" => "$keyword should be type Reference or valid Schema object"];
        }
        return ["success" => true];
    }

    protected function isSchemaOrReference($value): bool
    {
        if (($value instanceof Schema) || ($value instanceof Reference)) {
            return true;
        }
        return false;
    }

    public function setItem( $schema, $key=null, $reference = false){
        if(!$reference)
            $instance = new Schema();
        else
            $instance = new Reference();

        $instance = $this->setInstanceOfObject($instance, $schema);

        if ($key == null)
            // array_push($this->items, $instance);
            $this->items = $instance;
        else
            $this->items[$key] = $instance;
    }
}
