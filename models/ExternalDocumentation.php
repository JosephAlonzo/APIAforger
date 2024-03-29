<?php 
namespace Models;

class ExternalDocumentation extends Base
{
    /**
     * A short description of the target documentation. CommonMark syntax MAY be used for rich text representation.
     */
    public string $description;
    /**
     * REQUIRED. The URL for the target documentation. Value MUST be in the format of a URL. 
     */
    public string $url;

    function __construct(string $url = '')
    {
        $this->url = $url;
        $this->modelProperties = ["url"];
        $this->requiredProperties = ["url"];
        $this->initialize();
    }
    private function initialize(){
        $this->description = '';
    }

    protected function isValidUrl(){
        if ( $this->isValidURLFormat($this->url) )
            return [ "success" => false, "message" => "url MUST be a url valid format" ];
        return ["success" => true ];
    }
}