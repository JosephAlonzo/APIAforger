<?php
namespace Models;


class Components extends Base
{
    /**
    * An object to hold reusable Schema Objects.
    * Map[string, Schema Object | Reference Object]
    */
    public array $schemas;
    /**
    * An object to hold reusable Response Objects.
    */
    public array $responses;
    /**
    * An object to hold reusable Parameter Objects.
    */
    public array $parameters;
    /**
    * An object to hold reusable Example Objects.
    */
    public array $examples;
    /**
    * An object to hold reusable Request Body Objects.
    */
    public array $requestBodies;
    /**
    * An object to hold reusable Header Objects.
    */
    public array $headers;
    /**
    * An object to hold reusable Security Scheme Objects.
    */
    public array $securitySchemes;
    // /**
    // An object to hold reusable Link Objects.
    // 
    // public array $links;
    // /**
    // An object to hold reusable Callback Objects.
    // 
    // public array $callbacks;

    function __construct()
    {
        $this->initialize();
        $this->modelProperties = [
            "schemas",
            "parameters",
            "examples",
            "requestBodies",
            "headers",
            "securitySchemes",
            // "links",
            // "callbacks",
        ];
    }

    private function initialize(){
        $this->schemas = [];
        $this->responses = [];
        $this->parameters = [];
        $this->examples = [];
        $this->requestBodies = [];
        $this->headers = [];
        $this->securitySchemes = [];
    }

    public function setSchemas( $schema, $key=null, $reference = false){
        if(!$reference)
            $instance = new Schema();
        else
            $instance = new Reference();

        $instance = $this->setInstanceOfObject($instance, $schema);

        if ($key == null)
            array_push($this->schemas, $instance);
        else
            $this->schemas[$key] = $instance;
    }

    public function setSecuritySchemes( $schema, $key=null, $reference = false){
        if(!$reference)
            $instance = new SecurityScheme();
        else
            $instance = new Reference();
        
        $instance = $this->setInstanceOfObject($instance, $schema);

        if ($key == null)
            array_push($this->securitySchemes, $instance);
        else
            $this->securitySchemes[$key] = $instance;
    }


    protected function isValidSchemas(){
        if( !$this->is_associative_array($this->schemas) ) 
            return [ "success" => false, "message" => "schemas MUST be an associative array" ];
            
        foreach ($this->schemas as $index => $value) {
            $isExpectedObject = ($value instanceof Schema) || ($value instanceof Reference) ? true: false;
            if( !$isExpectedObject )
                return ["success" => false, "message" => "one or more of your schemas is not a valid type MUST be of type Schema|Reference"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "object is not passing the validation index $index"];
        }
        return ["success" => true];
    }
    protected function isValidResponses(){
        if( !$this->is_associative_array($this->responses) ) 
            return [ "success" => false, "message" => "responses MUST be an associative array" ];

        foreach ($this->responses as $index => $value) {
            $isExpectedObject = ($value instanceof Response) || ($value instanceof Reference) ? true: false;
            if( !$isExpectedObject )
                return ["success" => false, "message" => "responses MUST be of type Response|Reference"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "object is not passing the validation index $index"];
        }
        return ["success" => true];
    }
    protected function isValidParameters(){
        if( !$this->is_associative_array($this->parameters) ) 
            return [ "success" => false, "message" => "parameters MUST be an associative array" ];

        foreach ($this->parameters as $index => $value) {
            $isExpectedObject = ($value instanceof Parameter) || ($value instanceof Reference) ? true: false;
            
            if( !$isExpectedObject )
                return ["success" => false, "message" => "parameters MUST be of type Parameter|Reference"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "object is not passing the validation index $index"];
        }
        return ["success" => true];
    }
    protected function isValidExamples(){
        if( !$this->is_associative_array($this->examples) ) 
            return [ "success" => false, "message" => "examples MUST be an associative array" ];
        
        foreach ($this->examples as $index => $value) {
            $isExpectedObject = ($value instanceof Example) || ($value instanceof Reference) ? true: false;

            if( !$isExpectedObject )
                return ["success" => false, "message" => "examples MUST be of type Example|Reference"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "object is not passing the validation index $index"];
        }
        return ["success" => true];
    }
    protected function isValidRequestBodies(){
        if( !$this->is_associative_array($this->requestBodies) ) 
            return [ "success" => false, "message" => "requestBodies MUST be an associative array" ];

        foreach ($this->requestBodies as $index => $value) {
            $isExpectedObject = ($value instanceof RequestBody) || ($value instanceof Reference) ? true: false;
            if( !$isExpectedObject )
                return ["success" => false, "message" => "requestBodies MUST be of type RequestBody|Reference"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "object is not passing the validation index $index"];
        }
        return ["success" => true];
    }
    protected function isValidHeaders(){
        if( !$this->is_associative_array($this->headers) ) 
            return [ "success" => false, "message" => "headers MUST be an associative array" ];

        foreach ($this->headers as $index => $value) {
            $isExpectedObject = ($value instanceof Header) || ($value instanceof Reference) ? true: false;
            if( !$isExpectedObject )
                return ["success" => false, "message" => "headers MUST be of type Header|Reference"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "object is not passing the validation index $index"];    
        }
        return ["success" => true];
    }
    protected function isValidSecuritySchemes(){
        if( !$this->is_associative_array($this->securitySchemes) ) 
            return [ "success" => false, "message" => "securitySchemes MUST be an associative array" ];

        foreach ($this->securitySchemes as $index => $value) {
            $isExpectedObject = ($value instanceof SecurityScheme) || ($value instanceof Reference) ? true: false;

            if( !$isExpectedObject )
                return ["success" => false, "message" => "securitySchemes MUST be of type SecurityScheme|Reference"];
            if( !$value->isValid() )
                return ["success" => false, "message" => "object is not passing the validation index $index"];  
        }
        return ["success" => true];
    }
    // protected function isValidLinks(){
    //     foreach ($this->links as $value) {
    //         if( !is_string($value) && !($value instanceof Link) && !($value instanceof Reference) )
    //             return ["success" => false, "message" => "links MUST be of type Parameter|Reference"];
    //     }
    //     return ["success" => true];
    // }
    // protected function isValidCallbacks(){
    //     foreach ($this->callbacks as $value) {
    //         if( !is_string($value) && !($value instanceof Callback) && !($value instanceof Reference) )
    //             return ["success" => false, "message" => "callbacks MUST be of type Callback|Reference"];
    //     }
    //     return ["success" => true];
    // }
}

// $c = new Component();
// $c->isValid();