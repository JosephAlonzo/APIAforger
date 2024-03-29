<?php
namespace Models;


class Response extends Base
{
    /** REQUIRED. A short description of the response */
    public string $description = '';

    /**
     * Maps a header name to its definition. 
     * RFC7230 states header names are case insensitive. 
     * If a response header is defined with the name "Content-Type", it SHALL be ignored.
     * Map[string, Header Object | Reference Object]
     */
    public array $headers= [];
    /**
     * A map containing descriptions of potential response payloads. 
     * The key is a media type or media type range and the value describes it. 
     * For responses that match multiple keys, only the most specific key is applicable. e.g. text/plain overrides text/*
     * 	Map[string, Media Type Object]
     */
    public array $content = [];
    // 
    //  A map of operations links that can be followed from the response. 
    //  The key of the map is a short name for the link, following the naming constraints of the names for Component Objects.
    //  Map[string, Link Object | Reference Object]
    // public array $links;

    function __construct(string $description = ''){
        $this->descripcion = $description;
        $this->modelProperties = [
            'descripcion',
            'headers',
            'content',
            // 'links',
        ];
    }

    public function setHeaders($header, $key,$reference = false){
        if( !$reference ){
            $instance = new Header();
        }
        else{
            $instance = new Reference();
        }
        $instance = $this->setInstanceOfObject($instance, $header);
        $this->headers[$key] = $instance;
    }

    public function setContent( $content, $key){
        if( is_object( $content) ){
            $this->content[$key] = $content;
        }
        else{
            $instance = new MediaType();
            $instance = $this->setInstanceOfObject($instance, $content);
            $this->content[$key] = $instance;
        }
    }

    public function isValid(): bool
    {
        return $this->makeValidations();
    }

    public function isValidDescription(){
        if( !is_string( $this->descripcion ) || !$this->descripcion ) 
            return [ "success" => false, "description MUST be string and MUST be not empty" ];
        return ["success" => true];
    }

    public function isValidHeader(){
        if(!is_array($this->headers) && count($this->headers) ) return [ "success" => false, "header MUST be of type array and MUST be not empty" ];
        foreach ($this->headers as $value) {
            if( !is_string( $value ) && !($value instanceof Header) && !($value instanceof Reference) ) 
                return [ "success" => false, "header MUST be of type string|Header|Reference" ];
        }
        return ["success" => true];
    }

    public function isValidContent(){
        if(!is_array($this->content) && count($this->content) ) return [ "success" => false, "content MUST be of type array and MUST be not empty" ];
        foreach ($this->content as $value) {
            if( !is_string( $value ) && !($value instanceof MediaType) ) 
                return [ "success" => false, "header MUST be of type string|MediaType" ];
        }
        return ["success" => true];
    }

    // public function isValidLinks(){
    //     if(!is_array($this->links) && count($this->links) ) return [ "success" => false, "links MUST be of type array and MUST be not empty" ];
    //     foreach ($this->links as $value) {
    //         if( !is_string( $value ) && !($value instanceof Link) && !($value instanceof Reference) ) 
    //             return [ "success" => false, "header MUST be of type string|MediaType" ];
    //     }
    //     return ["success" => true];
    // }
}