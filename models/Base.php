<?php 
namespace Models;

abstract class Base 
{
    public $errors = [];
    protected $modelProperties = [];
    protected $requiredProperties = [];
    protected $defaultClassProperties = [ "errors", "modelProperties", "requiredProperties", "defaultClassProperties" ];

    function __construct()
    {
        $this->errors = [];
    }

    public function makeValidations(): bool{
        foreach ($this->modelProperties as $property) {
            if( !$this->evaluateProperty($property) ) 
                continue;
            $method_name = "isValid" . ucfirst($property);
            //if validation method not exist its mean that there is not validation so it will be true by default
            if( !method_exists($this, $method_name))
                continue;
            $response = $this->{$method_name}();
            if (!$response['success']) 
                array_push($this->errors, $response['message']);
        }
        return count($this->errors) ? false : true;
    }

    private function evaluateProperty($property): bool
    {
        //if $property is required then verify if have a value to evaluate
        if(in_array($property, $this->requiredProperties)){
            if(!isset($this->$property) || !$property){
                array_push($this->errors, "{$property} is required");
                return false;
            }  
            return true;
        }
        //if property is optional and have a value then verify if is valid if not just pass 
        elseif(isset($this->$property) && $property) 
            return true; 
        return false;
    }

    public function isValid(): bool
    {
        return $this->makeValidations();
    }

    protected function isValidURLFormat(string $url): bool{
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
           false;
        return true;
    }

    protected function isValidEmailFormat(string $url): bool{
        if (filter_var($url, FILTER_VALIDATE_EMAIL) === FALSE)
           false;
        return true;
    }

    /**
     * Checks if an array is associative. Return value of 'False' indicates a sequential array.
     * @param array $inpt_arr 
     * @return bool 
     */
    function is_associative_array(array $inpt_arr): bool
    {
        // An empty array is in theory a valid associative array
        // so we return 'true' for empty.
        if ([] === $inpt_arr) {
            return false;
        }
        $n = count($inpt_arr);
        for ($i = 0; $i < $n; $i++) {
            if(array_key_exists($i, $inpt_arr)) {
                return false;
            }
        }
        // Dealing with a Sequential array
        return true;
    }

    protected function setInstanceOfObject( object $instance, $value ){
        $instanceClass = get_class($instance) == "Models\Property" ? "Models\Schema" : get_class($instance);
        $valueClass = get_class($value) == "Models\Property" ? "Models\Schema" : get_class($value);
        if ( gettype($value) !== 'object' || $instanceClass != $valueClass ) 
            try {
                $instance->parse($value);
            } catch (\Throwable $th) {
                throw $th;
            } 
        else
            $instance = $value;
        return $instance;
    }

    public function parse(array $array){
        foreach ($array as $key => $value)
        {
            if($this->isADefaultProperty($key) ) continue;

            if(is_object($this->$key)){
                $output = get_class($this->$key);
                $newObject = new $output();
                $newObject->parse($value);
                $this->$key = $newObject;
            }
            elseif(is_array($this->$key)){
                foreach ($value as $index => $element) {
                    if($this->isADefaultProperty($key)) continue;
                    $setName = "set".ucfirst($key);
                    $this->$setName($element, $index);
                }
            }
            else{
                $this->$key = $value;
            }
        }
    }

    public function convertToJson($element, $response = []){
        foreach ($element as $key => $value) {
            $serializeNullValues = false;
            if($this->isADefaultProperty($key) ) continue;
            
            if($key == "security" ) {
                $serializeNullValues = true;
            }
            if( is_array($value) || is_object($value) ){
                if( $result = $this->serialize( $value, [] , $serializeNullValues) ){
                    if($key == "paths"){
                        //Doublon with paths key because obj and property have same name
                        $result = $result["paths"];
                    }
                    $response[$key] = $result;
                }
            }
            else{
                $response[$key] = $value;
            }
        }
        
        return json_encode($response, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    public function serialize($element, $tmp = [], $serializeNullValues= false, $is_associative_array = false){
        foreach ($element as $key => $value) {
            if( isset($element->defaultClassProperties) ){
                $this->defaultClassProperties = $element->defaultClassProperties;
            }
            $exceptionNull = ( $key === "explode" && !$value )  ? true : false;
        
            if( $this->isADefaultProperty($key) || ( ( ( !$value || $value == [] ) && !$serializeNullValues) && !$exceptionNull) )
                continue;

            if(  is_object($value) || is_array($value) ){
                
                $result = $this->serialize( $value, [], $serializeNullValues, $this->is_associative_array($value));
                if(!$result && !$serializeNullValues) continue;

                if($key === "HTTPStatusCode"){
                    foreach ($result as $statusCode => $bodyResponse) {
                        $tmp[$statusCode] = $bodyResponse;
                    }
                }
                elseif( is_numeric($key) && !$is_associative_array){
                    array_push($tmp, $result );
                }
                else
                    $tmp[$key] = $result; 
            }
            else {
                $key = ($key === "ref") ? '$'.$key: $key;
                $value = ( is_bool($value) ) ? $value : html_entity_decode($value);
                if( is_numeric($key) ){
                    array_push($tmp, $value);
                }
                else{
                    $tmp[$key] = $value;
                }
            }
                
        }
        return $tmp;
    }

    protected function isADefaultProperty($property){
        if(in_array($property, $this->defaultClassProperties, true))
            return true;
        return false;
    }
}