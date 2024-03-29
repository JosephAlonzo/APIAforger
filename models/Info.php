<?php
namespace Models;

class Info extends Base
{
    /**
     * REQUIRED. The title of the API.
     */
    public string $title;
    /**
     * A short description of the API. CommonMark syntax MAY be used for rich text representation.
     */
    public string $description;
    /**
     * A URL to the Terms of Service for the API. MUST be in the format of a URL.
     */
    public string $termsOfService;
    /**
     * 	License Object	The license information for the exposed API.
     */
    public License $license;
    /**
     * 	The contact information for the exposed API.
     */
    public Contact $contact;
    /**
     * 	REQUIRED. The version of the OpenAPI document (which is distinct from the OpenAPI Specification version or the API implementation version).
     */
    public string $version;

    function __construct(?string $title = '', ?string $version= '' ) {
        $this->title = $title;
        $this->version = $version;
        $this->initialize();
        $this->modelProperties = ["title", "version", "termsOfService", "contact", "license"];
        $this->requiredProperties = ["title", "version"];
    }

    private function initialize(){
        $this->description = '';
        $this->termsOfService = '';
        $this->license = new License();
        $this->contact = new Contact();
    }

    protected function isValidTermsOfService(){
        if ( $this->isValidURLFormat($this->url) )
            return [ "success" => false, "message" => "termsOfService MUST be a url valid format" ];
        return ["success" => true ];
    }

    protected function isValidLicense(){
        if ( !($this->license instanceof License) )
            return [ "success" => false, "message" => "License MUST be an instance of License" ];
        
        if(!$this->license->isValid())
            return [ "success" => false, "message" => "License MUST be a valid License object" ];

        return ["success" => true ];
    }

    protected function isValidContact(){
        if ( !($this->contact instanceof Contact) )
            return [ "success" => false, "message" => "contact MUST be an instance of Contact" ];
        
        if(!$this->contact->isValid())
            return [ "success" => false, "message" => "contact MUST be a valid contact object" ];

        return ["success" => true ];
    }
}
