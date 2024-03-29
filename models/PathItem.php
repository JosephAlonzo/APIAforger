<?php 
namespace Models;


class PathItem extends Base
{
    /**
     * Allows for an external definition of this path item. 
     * The referenced structure MUST be in the format of a Path Item Object. 
     * In case a Path Item Object field appears both in the defined object and the referenced object, the behavior is undefined.
     */
    public string $ref = '';
    /**
     * An optional, string summary, intended to apply to all operations in this path.
     */
    public string $summary = '';
    /**
     * An optional, string description, intended to apply to all operations in this path.
     */
    public string $description = '';
    /**
     * A definition of a GET operation on this path.
     */
    public Operation $get;
    /**
     * A definition of a PUT operation on this path.
     */
    public Operation $put;
    /**
     * A definition of a POST operation on this path.
     */
    public Operation $post;
    /**
     * A definition of a DELETE operation on this path.
     */
    public Operation $delete;
    /**
     * A definition of a OPTIONS operation on this path.
     */
    public Operation $options;
    /**
     * A definition of a HEAD operation on this path.
     */
    public Operation $head;
    /**
     * A definition of a PATCH operation on this path.
     */
    public Operation $patch;
    /**
     * A definition of a TRACE operation on this path.
     */
    public Operation $trace;
    /**
     * An alternative server array to service all operations in this path.
     * 
     * [Server Object]
     */
    public array $servers = [];
    /**
     * A list of parameters that are applicable for all the operations described under this path. These parameters can be overridden at the operation level, 
     * but cannot be removed there. The list MUST NOT include duplicated parameters. 
     * A unique parameter is defined by a combination of a name and location. 
     * The list can use the Reference Object to link to parameters that are defined at the OpenAPI Object's components/parameters.
     * 
     * 	[Parameter Object | Reference Object]
     */
    public array $parameters = [];

    function __construct()
    {
        $this->modelProperties = ["get", "put", "post", "delete", "options", "head", "patch", "trace", "servers", "parameters"];
        $this->get = new Operation();
        $this->put = new Operation();
        $this->post = new Operation();
        $this->delete = new Operation();
        $this->options = new Operation();
        $this->head = new Operation();
        $this->patch = new Operation();
        $this->trace = new Operation();

    }
    protected function isValidGet(){
        return $this->isValidOperation($this->get, "get");
    }
    protected function isValidPut(){
        return $this->isValidOperation($this->put, "put");
    }
    protected function isValidPost(){
        return $this->isValidOperation($this->post, "post");
    }
    protected function isValidDelete(){
        return $this->isValidOperation($this->delete, "delete");
    }
    protected function isValidOptions(){
        return $this->isValidOperation($this->options, "options");
    }
    protected function isValidHead(){
        return $this->isValidOperation($this->head, "head");
    }
    protected function isValidPatch(){
        return $this->isValidOperation($this->patch, "patch");
    }
    protected function isValidTrace(){
        return $this->isValidOperation($this->trace, "trace");
    }
    protected function isValidServers(){
        foreach ($this->servers as $key => $property) {
            if(!($property instanceof Server))
                return [ "success"=> false, "message" => "the element: $key is not a instance of server object"];
            if(!$property->isValid())
                return [ "success"=> false, "message" => "the element: $key is not a Valid Server object"];
        }
        return [ "success"=> true];
    }

    protected function isValidParameters(){
        foreach ($this->parameters as $key => $property) {
            if(!($property instanceof Parameter) && !($property instanceof Reference))
                return [ "success"=> false, "message" => "the element: $key is not a instance of Parameter|Reference object"];
            if(!$property->isValid())
                return [ "success"=> false, "message" => "the element: $key is not a Valid Parameter|Reference object"];
        }
        return [ "success"=> true];
    }

    protected function isValidOperation(Operation $property, string $propertyName){
        if(!$property->isValid())
            return [ "success"=> false, "message" => "$propertyName is not a Valid Operation object"];
        return [ "success"=> true];
    }
}