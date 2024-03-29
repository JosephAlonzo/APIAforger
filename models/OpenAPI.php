<?php
namespace Models;


class OpenAPI extends Base
{
    /**
     * REQUIRED. This string MUST be the semantic version number of the OpenAPI Specification version that the OpenAPI document uses. 
     * The openapi field SHOULD be used by tooling specifications and clients to interpret the OpenAPI document. This is not related to the API info.version string.
    */
    public string $openapi;
    /**
     * REQUIRED. Provides metadata about the API. The metadata MAY be used by tooling as required.
     */
    public null|Info $info;
    /**
     * An array of Server Objects, which provide connectivity information to a target server. 
     * If the servers property is not provided, or is an empty array, the default value would be a Server Object with a url value of /.
     */
    public array $servers;
    /**
     * REQUIRED. The available paths and operations for the API.
     */
    public null|Paths $paths;
    /**
     * An element to hold various schemas for the specification.
     */
    public Components $components;
    /**
     * A declaration of which security mechanisms can be used across the API. 
     * The list of values includes alternative security requirement objects that 
     * can be used. Only one of the security requirement objects need to be satisfied to authorize a request. 
     * Individual operations can override this definition. To make security optional, an empty security requirement ({}) can be included in the array.
     */
    public array $security;
    /**
     * A list of tags used by the specification with additional metadata. 
     * The order of the tags can be used to reflect on their order by the parsing tools. 
     * Not all tags that are used by the Operation Object must be declared. 
     * The tags that are not declared MAY be organized randomly or based on the tools' logic. Each tag name in the list MUST be unique.
     */
    public array $tags;
    /**
     * Additional external documentation.
     */
    public ExternalDocumentation $externalDocs;

    function __construct() {
        $this->initialize();
        $this->modelProperties = ["openapi", "info", "paths", "components", "tags", "externalDocs", "servers", "security"];
        $this->requiredProperties = ["openapi", "info", "paths"];
    }

    private function initialize(){
        $this->openapi = '';
        $this->info = new Info();
        $this->paths = new Paths();
        $this->servers = [];
        $this->components = new Components();
        $this->security = [];
        $this->tags = [];
        $this->externalDocs =  new ExternalDocumentation();
    }

    public function setServers($server, $index=null){
        $instance = new Server();
        $instance = $this->setInstanceOfObject($instance, $server);
        if($index != null)
            $this->servers[$index] = $instance;
        else
            array_push($this->servers, $instance);
    }

    public function setTags($tag, $index=null){
        $instance = new Tags();
        $instance = $this->setInstanceOfObject($instance, $tag);
        
        if($index != null)
            $this->tags[$index] = $instance;
        else
            array_push($this->tags, $instance);
    }

    public function setSecurity( array $value, $index=null){
        $security = [
            $index => $value
        ];
        array_push($this->security, $security);
    }

    public function setComponents($components, $index=null){
        $instance = new Components();
        $instance = $this->setInstanceOfObject($instance, $components);

        $this->components = $instance;
    }

    protected function isValidServers(){
        foreach ($this->servers as $key => $value) {
            if(!($value instanceof Server))
                return ["success"=> true, "message" => "All the servers MUST be of type server, the index $key seem not be a server object" ];
            if( !$value->isValid() )
                return ["success"=> false, "message" => "Server object is not valid, index: $key " ];
        }
        return ["success" => true];
    }

    protected function isValidSecurity(){
        foreach ($this->security as $key => $value) {
            if(!($value instanceof SecurityRequirement))
                return ["success"=> true, "message" => "All the security MUST be of type SecurityRequirement, the index $key seem not be a SecurityRequirement object" ];
            if( !$value->isValid() )
                return ["success"=> false, "message" => "security object is not valid, index: $key " ];
        }
        return ["success" => true];
    }

    protected function isValidInfo(){
        if( !$this->info->isValid() )
            return ["success"=> false, "message" => "info object is not valid" ];
        return ["success" => true];
    }

    protected function isValidPaths(){
        if( !$this->paths->isValid() )
            return ["success"=> false, "message" => "Paths object is not valid" ];
        return ["success" => true];
    }

    protected function isValidComponents(){
        if( !$this->components->isValid() )
            return ["success"=> false, "message" => "components object is not valid" ];
        return ["success" => true];
    }

    protected function isValidTags(){
        foreach ($this->tags as $tag) {
            if( !$tag->isValid() )
                return ["success"=> false, "message" => "tags object is not valid" ];
        }
        return ["success" => true];
    }

    protected function isValidExternalDocs(){
        if( !$this->externalDocs->isValid() )
            return ["success"=> false, "message" => "externalDocs object is not valid" ];
        return ["success" => true];
    }

}