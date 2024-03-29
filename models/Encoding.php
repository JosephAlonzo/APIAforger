<?php
namespace Models;


class Encoding extends Base
{
    /**
     * The Content-Type for encoding a specific property. 
     * Default value depends on the property type: 
     * for string with format being binary – application/octet-stream; 
     * for other primitive types – text/plain; 
     * for object - application/json; 
     * for array – the default is defined based on the inner type. The value can be a specific media type (e.g. application/json), 
     * a wildcard media type (e.g. image/*), or a comma-separated list of the two types
     */
    public string $contentType;
    /**
     * A map allowing additional information to be provided as headers, for example Content-Disposition. 
     * Content-Type is described separately and SHALL be ignored in this section. 
     * This property SHALL be ignored if the request body media type is not a multipart.
     * Map[string, Header Object | Reference Object]
     */
    public array $headers;
    /**
     * Describes how a specific property value will be serialized depending on its type. 
     * See Parameter Object for details on the style property. 
     * The behavior follows the same values as query parameters, including default values. 
     * This property SHALL be ignored if the request body media type is not application/x-www-form-urlencoded.
     */
    public string $style;
    /**
     * When this is true, property values of type array or object generate separate parameters for each value of the array, or key-value-pair of the map. 
     * For other types of properties this property has no effect. 
     * When style is form, the default value is true. 
     * For all other styles, the default value is false. This property SHALL be ignored if the request body media type is not application/x-www-form-urlencoded.
     */
    public bool $explode;

    /**
     * Determines whether the parameter value SHOULD allow reserved characters, as defined by RFC3986 :/?#[]@!$&'()*+,;= to be included without percent-encoding. 
     * The default value is false. This property SHALL be ignored if the request body media type is not application/x-www-form-urlencoded.
     */
    public bool $allowReserved;

    function __construct()
    {
        $this->modelProperties = ["headers"];
        $this->initialize();
    }

    private function initialize(){
        $this->contentType = '';
        $this->headers = '';
        $this->style = '';
        $this->explode = false;
        $this->allowReserved = false;
      }
    protected function isValidHeaders(){
        if($this->is_associative_array($this->headers) )
        return ["success" => false, "message" => "headers MUST be an associtive array"];

        foreach ($this->headers as $key=>$value) {
            if( !($value instanceof Header) ) 
                return ["success" => false, "message" => "headers MUST be type Header"];

            if($value->isValid()) 
                return ["success" => false, "message" => "headers object is not valid, index $key"];
        }
        return ["success" => true];
    }

}