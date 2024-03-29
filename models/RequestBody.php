<?php
namespace Models;


class RequestBody extends Base
{
    /**
     * A brief description of the request body. This could contain examples of use.
     */
    public string $description = '';
    /**
     * REQUIRED. 
     * The content of the request body. The key is a media type or media type range and the value describes it. 
     * For requests that match multiple keys, only the most specific key is applicable. e.g. text/plain overrides text/*
     * 
     * Map[string, Media Type Object]
     */
    public array $content = [];
    /**
     * 	Determines if the request body is required in the request. Defaults to false.
     */
    public bool $required = false;

    function __construct(array $content = [])
    {
        $this->content = $content;
        $this->modelProperties = [
            'content'
        ];
        $this->requiredProperties = ['content'];
    }

    public function setContent($content, $key){
        if( is_object( $content) ){
            $this->content[$key] = $content;
        }
        else{
            $instance = new MediaType();
            $instance = $this->setInstanceOfObject($instance, $content);
            $this->content[$key] = $instance;
        }
    }

    protected function isValidContent(){
        foreach ($this->content as $value) {
            if( !is_string($value) && !($value instanceof MediaType) )
                return ["success" => false, "message" => "schemas MUST be of type string|MediaType"];
        }
        return ["success" => true];
    }

}