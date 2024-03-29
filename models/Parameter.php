<?php
namespace Models;

class Parameter extends Base
{
    /**
     * REQUIRED. The name of the parameter. Parameter names are case sensitive.
     * If in is "path", the name field MUST correspond to a template expression occurring within the path field in the Paths Object. See Path Templating for further information.
     * If in is "header" and the name field is "Accept", "Content-Type" or "Authorization", the parameter definition SHALL be ignored.
     * For all other cases, the name corresponds to the parameter name used by the in property. 
     */
    public string $name = '';
    /**
     * REQUIRED. The location of the parameter. 
     * Possible values are "query", "header", "path" or "cookie".
     */
    public string $in = '';
    /**A brief description of the parameter. This could contain examples of use. */
    public string $description = '';
    /**
     * Determines whether this parameter is mandatory. 
     * If the parameter location is "path", 
     * this property is REQUIRED and its value MUST be true. 
     * Otherwise, the property MAY be included and its default value is false.
     */
    public bool $required = false;
    /**
     * Specifies that a parameter is deprecated and SHOULD be transitioned out of usage. Default value is false.
     */
    public bool $deprecated = false;
    /**
     * Sets the ability to pass empty-valued parameters. This is valid only for query parameters and allows sending a parameter with an empty value. 
     * Default value is false. 
     * If style is used, and if behavior is n/a (cannot be serialized), the value of allowEmptyValue SHALL be ignored. 
     * Use of this property is NOT RECOMMENDED, as it is likely to be removed in a later revision.
     */
    public bool $allowEmptyValue;

    //The rules for serialization of the parameter are specified in one of two ways. 
    //For simpler scenarios, a schema and style can describe the structure and syntax of the parameter.
    /**
     * Describes how the parameter value will be serialized depending on the type of the parameter value. 
     * Default values (based on value of in): for query - form; for path - simple; for header - simple; for cookie - form.
     */
    public string $style= '';
    /**
     * When this is true, parameter values of type array or object generate separate parameters for each value of the array or key-value pair of the map. 
     * For other types of parameters this property has no effect. When style is form, the default value is true. 
     * For all other styles, the default value is false.
     */
    public bool $explode= false;
    /**
     * Determines whether the parameter value SHOULD allow reserved characters, as defined by RFC3986 :/?#[]@!$&'()*+,;= to be included without percent-encoding. 
     * This property only applies to parameters with an in value of query. The default value is false.
     */
    public bool $allowReserved = false;
    /**
     * The schema defining the type used for the parameter.
     * @parms Schema Object | Reference Object
     */
    public Schema|Reference $schema;
    /**
     * Example of the parameter's potential value. The example SHOULD match the specified schema and encoding properties if present. 
     * The example field is mutually exclusive of the examples field. 
     * Furthermore, if referencing a schema that contains an example, the example value SHALL override the example provided by the schema. 
     * To represent examples of media types that cannot naturally be represented in JSON or YAML, a string value can contain the example with escaping where necessary.
     */
    public $example;
    /**
     * Examples of the parameter's potential value. 
     * Each example SHOULD contain a value in the correct format as specified in the parameter encoding. 
     * The examples field is mutually exclusive of the example field. 
     * Furthermore, if referencing a schema that contains an example, the examples value SHALL override the example provided by the schema.
     * Map[ string, Example Object | Reference Object]
     * @parms Map[ string, Example Object | Reference Object]
     */
    public array $examples = []; 

    function __construct(?string $name = '', ?string $in= '')
    {
        $this->name = $name;
        $this->in = $in;
        $this->modelProperties = [
            'name',
            'in',
            'schema'
        ];
        $this->requiredProperties = [ "name", "in" ];
        $this->schema = new Schema();
    }
    // The name of the parameter. Parameter names are case sensitive.
    // If in is "path", the name field MUST correspond to a template expression occurring within the path field in the Paths Object. See Path Templating for further information.
    // If in is "header" and the name field is "Accept", "Content-Type" or "Authorization", the parameter definition SHALL be ignored.
    // For all other cases, the name corresponds to the parameter name used by the in property.
    public function isValidName(){
        switch ($this->in) {
            case 'path':
                # code...
                break;
            case 'header':
                # code...
                break;
            default:
                # code...
                break;
        }
        return [ "success" => true ];
    }

    public function isValidIn(){
        if(!is_string($this->in)) 
            return [ "success" => false, "message" => "in MUST be of type string"];
        $acceptedValues = [ "query", "header", "path", "cookie" ];
        if( !in_array($this->in, $acceptedValues) )
            return [ "success" => false, "message" => "in value MUST be query|header|path|cookie "];
        return [ "success" => true ];
    }

    public function isValidSchema(){
        // if( !($this->schema instanceof Schema) && !($this->schema instanceof Reference) ) 
        //     return [ "success" => false, "message" => "in MUST be of type Schema|Reference"];
        if( !$this->schema->isValid() )
            return [ "success" => false, "message" => "the schema is not valid"];
        return [ "success" => true ];
    }

}

?>