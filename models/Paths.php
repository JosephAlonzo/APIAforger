<?php 
namespace Models;


class Paths extends Base
{
    /**
     * A relative path to an individual endpoint. 
     * The field name MUST begin with a forward slash (/). 
     * The path is appended (no relative URL resolution) to the expanded URL from the Server Object's url field in order to construct the full URL. 
     * Path templating is allowed. When matching URLs, concrete (non-templated) paths would be matched before their templated counterparts. 
     * Templated paths with the same hierarchy but different templated names MUST NOT exist as they are identical. 
     * In case of ambiguous matching, it's up to the tooling to decide which one to use.
     * Map[string,Path Item Object]
     */
    public array $paths;

    function __construct(?array $paths = [])
    {
        $this->paths = $paths;
        $this->modelProperties = ["paths"];
        $this->requiredProperties = ["paths"];
    }

    public function setPath($pathItem, $path ){
        $instance = new PathItem();
        $instance = $this->setInstanceOfObject($instance, $pathItem);
        // $map = array($path => $instance);
        $this->paths[$path] = $instance;
        // array_push($this->paths, $map);
    }

    protected function isValidPath(){
        if( $this->is_associative_array($this->path) ) 
            return ["success"=>false, "message"=>"Path property SHOULD be un map"];
        foreach ($this->path as $key => $pathObj) {
            $tmpPath = explode( '/', trim($key) );
            $pathName = ($tmpPath[0] != "/") ? "/".$key : $key;

            if( !($pathObj instanceof PathItem))
                return ["success"=>false, "message"=>"Path property SHOULD be type Path"];
            if( !$pathObj->isValid() )
                return ["success"=>false, "message"=>"Path property is not a valid path"];
        }
    }

}