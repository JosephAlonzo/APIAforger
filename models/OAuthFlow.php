<?php 
namespace Models;


class OAuthFlow extends Base
{
    /**
     * custom property to identify which type of OAuthFlows object is calling OAuthFlow object
     */
    public string $type;
    /**
     * REQUIRED. The authorization URL to be used for this flow. This MUST be in the form of a URL.
     * Applies To oauth2 ("implicit", "authorizationCode")
     */
    public string $authorizationUrl;
    /**
     * REQUIRED. The token URL to be used for this flow. This MUST be in the form of a URL.
     * Applies To oauth2 ("password", "clientCredentials", "authorizationCode")
     */
    public string $tokenUrl;
    /**
     * 	The URL to be used for obtaining refresh tokens. This MUST be in the form of a URL.
     */
    public string $refreshUrl;
    /**
     * REQUIRED. The available scopes for the OAuth2 security scheme. A map between the scope name and a short description for it. The map MAY be empty.
     * Map[string, string]
     * 
     */
    public array $scopes;

    function __construct($type)
    {
        $this->type = $type;
        $this->modelProperties = ["type", "scopes"];
        $this->requiredProperties = ["scopes"];
    } 

    function isValidType(){
        $acceptedValues = ["implicit", "authorizationCode","password", "clientCredentials"];
        if(!in_array($this->type, $acceptedValues)) 
            return ["success" => false, "message" => "type MUST have one of these values implicit|authorizationCode|password|clientCredentials"];
        
        $propertiesRequired = [];    
        switch ($this->type) {
            case 'implicit':
                $propertiesRequired = ["authorizationUrl"];
                break;
            case 'authorizationCode':
                $propertiesRequired = ["authorizationUrl", "tokenUrl"];
                break;   
            case 'password':
                $propertiesRequired = ["tokenUrl"];
                break; 
            case 'clientCredentials':
                $propertiesRequired = ["tokenUrl"];
                break; 
            default:
                break;
        }
        
        array_push($this->modelProperties, ...$propertiesRequired);
        array_push($this->required, ...$propertiesRequired);
        return ["success" => true];
    }

    protected function isValidAuthorizationUrl(){
        if ( $this->isValidURLFormat($this->authorizationUrl) )
            return [ "success" => false, "message" => "authorizationUrl MUST be a url valid format" ];
        return ["success" => true ];
    }

    protected function isValidTokenUrl(){
        if ( $this->isValidURLFormat($this->tokenUrl) )
            return [ "success" => false, "message" => "tokenUrl MUST be a url valid format" ];
        return ["success" => true ];
    }

    protected function isValidRefreshUrl(){
        if ( $this->isValidURLFormat($this->refreshUrl) )
            return [ "success" => false, "message" => "refreshUrl MUST be a url valid format" ];
        return ["success" => true ];
    }

    protected function isValidScopes(){
        // if( !is_array($this->scopes) ) return [ "success" => false, "message" => "scopes MUST be array type" ];
        if(!$this->is_associative_array($this->scopes))
            return [ "success" => false, "message" => "scopes MUST have name and description for each value" ];
        return ["success" => true];
    }
}