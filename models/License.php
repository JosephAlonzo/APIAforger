<?php
namespace Models;

class License extends Base
{
    /**
     * REQUIRED. The license name used for the API.
     */
    public string $name;
    /**
     * A URL to the license used for the API. MUST be in the format of a URL.
     */
    public string $url;

    function __construct(?string $name='') {
        $this->name = $name;
        $this->initialize();
        $this->modelProperties = ["name", "url"];
        $this->requiredProperties = ["name"];
    }

    private function initialize(){
        $this->url = '';
    }

    protected function isValidUrl(){
        if ( $this->isValidURLFormat($this->url) )
            return [ "success" => false, "message" => "url MUST be a url valid format" ];
        return ["success" => true ];
    }
}