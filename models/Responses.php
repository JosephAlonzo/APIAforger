<?php 
namespace Models;



class Responses extends Base
{
    /**
     * The documentation of responses other than the ones declared for specific HTTP response codes. 
     * Use this field to cover undeclared responses. A Reference Object can link to a response that the OpenAPI Object's components/responses section defines.
     */
    public Response|Reference $default;
    /**
     * Any HTTP status code can be used as the property name, but only one property per code, to describe the expected response for that HTTP status code. A Reference Object can link to a response that is defined in the OpenAPI Object's components/responses section. This field MUST be enclosed in quotation marks (for example, "200") for compatibility between JSON and YAML. To define a range of response codes, this field MAY contain the uppercase wildcard character X. For example, 2XX represents all response codes between [200-299]. Only the following range definitions are allowed: 1XX, 2XX, 3XX, 4XX, and 5XX. If a response is defined using an explicit code, the explicit code definition takes precedence over the range definition for that code.
     * Response|Reference
     */
    public array $HTTPStatusCode = [];

    function __construct()
    {
        $this->default = new Response();
        $this->modelProperties = ["default", "HTTPStatusCode"];
    }

    public function setDefault($default, $reference = false){
        if( !$reference ){
            $instance = new Response();
        }
        else{
            $instance = new Reference();
        }
        $instance = $this->setInstanceOfObject($instance, $default);
        $this->default = $instance;
    }

    public function setHTTPStatusCode( $HTTPStatusCode,$key, $reference = false){
        if( !$reference ){
            $instance = new Response();
        }
        else{
            $instance = new Reference();
        }
        $instance = $this->setInstanceOfObject($instance, $HTTPStatusCode);
        $this->HTTPStatusCode[$key] = $instance;
    }

    protected function isValidDefault(){
        if(!($this->default instanceof Response) && !($this->default instanceof Reference))
            return [ "success"=> false, "message" => "Default is not a instance of Response|Reference object"];
        if(!$this->default->isValid())
            return [ "success"=> false, "message" => "Default is not a Valid Response|Reference object"];
        return [ "success"=> true];
    }

    protected function isValidHTTPStatusCode(){
        if(!($this->HTTPStatusCode instanceof Response) && !($this->HTTPStatusCode instanceof Reference))
            return [ "success"=> false, "message" => "HTTPStatusCode is not a instance of Response|Reference object"];
        if(!$this->default->isValid())
            return [ "success"=> false, "message" => "HTTPStatusCode is not a Valid Response|Reference object"];
        return [ "success"=> true];
    }

}