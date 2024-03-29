<?php
namespace Models;


class Contact extends Base
{
     /**
     * The identifying name of the contact person/organization.
     */
    public string $name;
    /**
     * The URL pointing to the contact information. MUST be in the format of a URL.
     */
    public string $url;
    /**
     * The email address of the contact person/organization. MUST be in the format of an email address.
     */
    public string $email;

    function __construct()
    {
        $this->initialize();
        $this->modelProperties = [ "url", "email"];
    }

    private function initialize(){
        $this->name = '';
        $this->url = '';
        $this->email = '';
    }

    protected function isValidUrl(){
        if ( $this->isValidURLFormat($this->url) )
            return [ "success" => false, "message" => "url MUST be a url valid format" ];
        return ["success" => true ];
    }

    protected function isValidEmail(){
        if ( $this->isValidEmailFormat($this->email) )
            return [ "success" => false, "message" => "email MUST be a url valid format" ];
        return ["success" => true ];
    }
}
