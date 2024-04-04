<?php
namespace Models;

class Operation extends Base{
    /**
     * A list of tags for API documentation control. Tags can be used for logical grouping of operations by resources or any other qualifier.
     */
    public array $tags = [];
    /**
     * A short summary of what the operation does.
     */
    public string $summary = '';
    /**
     * A verbose explanation of the operation behavior. CommonMark syntax MAY be used for rich text representation.
     */
    public string $description = '';
    /**
     * Additional external documentation for this operation.
     */
    public ExternalDocumentation $externalDocs;
    /**
     * Unique string used to identify the operation. The id MUST be unique among all operations described in the API. 
     * The operationId value is case-sensitive. 
     * Tools and libraries MAY use the operationId to uniquely identify an operation, therefore, it is RECOMMENDED to follow common programming naming conventions.
     */
    public string $operationId = '';
    /**
     * A list of parameters that are applicable for this operation. 
     * If a parameter is already defined at the Path Item, the new definition will override it but can never remove it. 
     * The list MUST NOT include duplicated parameters. A unique parameter is defined by a combination of a name and location. 
     * The list can use the Reference Object to link to parameters that are defined at the OpenAPI Object's components/parameters.
     * 	[Parameter Object | Reference Object]
     */
    public array $parameters = [];
    /**
     * The request body applicable for this operation. 
     * The requestBody is only supported in HTTP methods where the HTTP 1.1 specification RFC7231 has explicitly defined semantics for request bodies.
     * In other cases where the HTTP spec is vague, requestBody SHALL be ignored by consumers.
     */
    public $requestBody;
    /**
     * REQUIRED. The list of possible responses as they are returned from executing this operation.
     */
    public Responses $responses;
    // /**
    //  * A map of possible out-of band callbacks related to the parent operation. 
    //  * The key is a unique identifier for the Callback Object. 
    //  * Each value in the map is a Callback Object that describes a request that may be initiated by the API provider and the expected responses.
    //  */
    // public array $callbacks;
    /**
     * Declares this operation to be deprecated. Consumers SHOULD refrain from usage of the declared operation. Default value is false.
     */
    public bool $deprecated = false;
    /**
     * A declaration of which security mechanisms can be used for this operation. 
     * The list of values includes alternative security requirement objects that can be used. 
     * Only one of the security requirement objects need to be satisfied to authorize a request. 
     * To make security optional, an empty security requirement ({}) can be included in the array. 
     * This definition overrides any declared top-level security. To remove a top-level security declaration, an empty array can be used.
     */
    public array $security = [];
    /**
     * An alternative server array to service this operation. 
     * If an alternative server object is specified at the Path Item Object or Root level, it will be overridden by this value.
     */
    public array $servers = [];

    function __construct()
    {
        $this->modelProperties = [
            "responses", "externalDocs", "parameters", "requestBody", 
            // "security", "servers"
        ];
        $this->requiredProperties = ["responses"];
        $this->externalDocs = new ExternalDocumentation();
        $this->requestBody = new RequestBody();
        $this->responses = new Responses();
    }

    public function setRequestBody($requestBody, $reference = false, $index = null){
        if(!$reference) 
            $instance = new RequestBody(); 
        else 
            $instance = new Reference(); 
        $instance = $this->setInstanceOfObject($instance, $requestBody);
        $this->requestBody = $instance;
    }
   
    public function setParameter($parameter, $reference = false){
        if(!$reference) 
            $instance = new Parameter(); 
        else 
            $instance = new Reference(); 
        $instance = $this->setInstanceOfObject($instance, $parameter);
        array_push($this->parameters, $instance);
    }

    public function setTags(string $tag){
        array_push($this->tags, $tag);
    }

    public function setParameters( $parameter, $index=null, $reference = false){
        // foreach ($parameters as $parameter) {
        //     $reference = ($parameter == '$ref') ? true : false;
        //     $this->setParameter($parameter, $reference);
        // }
        if(!$reference) 
            $instance = new Parameter(); 
        else 
            $instance = new Reference(); 
        $instance = $this->setInstanceOfObject($instance, $parameter);
        $this->parameters[$index] = $instance;
    }

    protected function isValidExternalDocs(){
        if(!($this->externalDocs instanceof ExternalDocumentation) )
            return [ "success"=> false, "message" => "externalDocs is not a instance of ExternalDocumentation object"];
        if(!$this->externalDocs->isValid())
            return [ "success"=> false, "message" => "externalDocs is not a Valid ExternalDocumentation object"];
        return [ "success"=> true];
    }

    protected function isValidParameters(){
        foreach ($this->parameters as $key => $value) {
            if(!($value instanceof Parameter) && !($value instanceof Reference))
                return [ "success"=> false, "message" => "Parameters with index: $key is not a instance of Parameter|Reference object"];
            if(!$value->isValid())
                return [ "success"=> false, "message" => "Parameters with index: $key  is not a Valid Response|Reference object"];
        }
        return [ "success"=> true];
    }

    protected function isValidRequestBody(){
        if(!($this->requestBody instanceof RequestBody) && !($this->requestBody instanceof Reference))
            return [ "success"=> false, "message" => "requestBody is not a instance of RequestBody|Reference object"];
        if(!$this->requestBody->isValid())
            return [ "success"=> false, "message" => "requestBody is not a Valid RequestBody|Reference object"];
        return [ "success"=> true];
    }

    protected function isValidResponses(){
        if(!($this->responses instanceof responses) )
            return [ "success"=> false, "message" => "responses is not a instance of responses object"];
        if(!$this->responses->isValid())
            return [ "success"=> false, "message" => "responses is not a Valid responses object"];
        return [ "success"=> true];
    }

    protected function isValidSecurity(){
        foreach ($this->security as $key => $value) {
            if(!($value instanceof SecurityRequirement) )
                return [ "success"=> false, "message" => "security with index: $key is not a instance of SecurityRequirement object"];
            if(!$value->isValid())
                return [ "success"=> false, "message" => "security with index: $key is not a Valid SecurityRequirement object"];
        }
        return [ "success"=> true];
    }

    protected function isValidServers(){
        foreach ($this->security as $key => $value) {
            if(!($value instanceof Server) )
                return [ "success"=> false, "message" => "servers with index: $key is not a instance of Server object"];
            if(!$value->isValid())
                return [ "success"=> false, "message" => "servers with index: $key is not a Valid Server object"];
        }
        return [ "success"=> true];
    }

    // public function serialize($tmp = [], $element){
    //     if( is_object($element) ) $obj = $element; 
    //     else $obj = $this; 
    //     foreach ($element as $key => $value) {
    //         if( $obj->isADefaultProperty($key)) continue;

    //         if(  is_object($value) || is_array($value) ){
    //             $result = $obj->serialize([], $value);
    //             if(!$result) continue;

    //             if($key == "HTTPStatusCode"){
    //                 foreach ($result as $statusCode => $bodyResponse) {
    //                     $tmp[$statusCode] = $bodyResponse;
    //                 }
    //             }
    //             else{
    //                 $tmp[$key] = $result; 
    //             }
    //         }
    //         elseif( $value ){
    //             $tmp[$key] = $value;
    //         }
    //     }
    //     return $tmp;
    // }
}