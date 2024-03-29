<?php 
namespace Models;


class OAuthFlows extends Base
{
    public OAuthFlow $implicit;
    public OAuthFlow $password;
    public OAuthFlow $clientCredentials;
    public OAuthFlow $authorizationCode;

    function __construct()
    {
        $this->modelProperties = [
            "implicit",
            "password",
            "clientCredentials",
            "authorizationCode",
        ];
    }

    protected function isValidImplicit(){
        return $this->checkIfIsValid($this->implicit, "implicit");
    }
    protected function isValidPassword(){
        return $this->checkIfIsValid($this->password, "password");
    }
    protected function isValidClientCredentials(){
        return $this->checkIfIsValid($this->clientCredentials, "clientCredentials");
    }
    protected function isValidAuthorizationCode(){
        return $this->checkIfIsValid($this->implicit, "authorizationCode");
    }

    private function checkIfIsValid($value, $propertyName){
        if( !($value->isValid()) )
            return ["success" => false, "message" => "$propertyName MUST be a valid OAuthFlow object"];
        return ["success" => true];
    }
}