<?php 
namespace Models;


class MediaType extends Base
{
    /**
     * The schema defining the content of the request, response, or parameter.
     * Schema Object | Reference Object
     */
    public Schema|Reference $schema;
    /**
     * Example of the media type. The example object SHOULD be in the correct format as specified by the media type. 
     * The example field is mutually exclusive of the examples field. 
     * Furthermore, if referencing a schema which contains an example, the example value SHALL override the example provided by the schema.
     * Any
     */
    public mixed $example;
    /**
     * Examples of the media type. 
     * Each example object SHOULD match the media type and specified schema if present. 
     * The examples field is mutually exclusive of the example field. 
     * Furthermore, if referencing a schema which contains an example, the examples value SHALL override the example provided by the schema.
     * Map[ string, Example Object | Reference Object]
     */
    public array $examples;
    /**
     * A map between a property name and its encoding information. 
     * The key, being the property name, MUST exist in the schema as a property. 
     * The encoding object SHALL only apply to requestBody objects when the media type is multipart or application/x-www-form-urlencoded.
     * Map[string, Encoding Object]
     */
    public array $encoding;

    function __construct()
    {
        $this->modelProperties = ["schema", "examples", "encoding"];
    }

    public function setSchema($schema, $reference = false){
        if(!$reference) 
            $instance = new Schema(); 
        else 
            $instance = new Reference(); 
        
        $instance = $this->setInstanceOfObject($instance, $schema);
        $this->schema = $instance;
    }

    protected function isValidSchema(){
        if( !($this->schema instanceof Schema) && !($this->schema instanceof Reference) ) 
            return ["success" => false, "message" => "schema MUST be type Schema|Reference"];
        if(!$this->schema->isValid()) 
            return ["success" => false, "message" => "schema is not valid"];
        return ["success" => true];
    }

    protected function isValidExamples(){
        if($this->is_associative_array($this->examples) )
            return ["success" => false, "message" => "Examples MUST be an associtive array"];
        foreach ($this->examples as $index => $value) {
            if( !($value instanceof Example) && !($value instanceof Reference) ) 
                return ["success" => false, "message" => "one or more values of your array are not valid, example MUST be of type Example|Reference"];
            if(!$this->$value->isValid()) 
                return ["success" => false, "message" => "object is not valid"];
        }
        return ["success" => true];
    }

    protected function isValidEncoding(){
        if($this->is_associative_array($this->encoding) )
            return ["success" => false, "message" => "Encoding MUST be an associtive array"];

        foreach ($this->encoding as $value) {
            if( !($value instanceof Encoding) ) 
                return ["success" => false, "message" => "schema MUST be type Encoding"];

            if($value->isValid()) 
                return ["success" => false, "message" => "Encoding object is not valid"];
        }
      
        return ["success" => true];
    }
}

// $media = new MediaType();
// // $example = [
// //     "cat" => [
// //         "summary"=> "An example of a cat",
// //         "value"=> 
// //         [
// //             "name"=> "Fluffy",
// //             "petType"=> "Cat",
// //             "color"=> "White",
// //             "gender"=> "male",
// //             "breed"=> "Persian"
// //         ]
// //     ],
// //     "dog"=> [
// //         "summary"=> "An example of a dog with a cat's name",
// //         "value" =>  [
// //         "name"=> "Puma",
// //         "petType"=> "Dog",
// //         "color"=> "Black",
// //         "gender"=> "Female",
// //         "breed"=> "Mixed"
// //         ],
// //     ],
// //     "frog"=> [
// //         "ref"=> "#/components/examples/frog-example"
// //     ]
// //     ];
// $media->examples = $example;
// $media->isValid();

