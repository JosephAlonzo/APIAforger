<?php
namespace Models;

/** 
 * see more information of draf 00 
 * JSON Schema https://datatracker.ietf.org/doc/html/draft-zyp-json-schema-01#section-5.19
 */
class JSONSchema extends Base
{
    //following properties are taken directly from the JSON Schema definition and follow the same specifications
    public null|string $title = null;
    public null|int $multipleOf= null;
    public null|int $maximum= null;
    public mixed $exclusiveMaximum= null;
    public null|int $minimum= null;
    public mixed $exclusiveMinimum= null;
    public null|int $maxLength= null;
    public null|int $minLength= null;
    public null|string $pattern= null;
    public null|int $maxItems= null;
    public null|int $minItems= null;
    public null|bool $uniqueItems= null;
    public null|int $maxProperties= null;
    public null|int $minProperties= null;
    public array $required = [];
    public array $enum = [];
    public null|string $const = null; 

    function __construct()
    {
        $this->modelProperties = [
            'title',
            'multipleOf',
            'maximum',
            'exclusiveMaximum',
            'minimum',
            'exclusiveMinimum',
            'maxLength',
            'minLength',
            'pattern',
            'maxItems',
            'minItems',
            'uniqueItems',
            'maxProperties',
            'minProperties',
            'required',
            'enum',
            'const',
        ];

    }


    function isValid(): bool
    {
        return $this->makeValidations();
    }

    /**
     * - Date-time - This should be a date in ISO 8601 format of YYYY-MM-DDThh:mm:ssZ in UTC time.  
     *          This is the recommended form of date/timestamp.
     * 
     * - Date - This should be a date in the format of YYYY-MM-DD.  
     *          It is recommended that you use the "date-time" format instead of "date" 
     *          unless you need to transfer only the date part.
     * 
     * - time - This should be a time in the format of hh:mm:ss.  
     *          It is recommended that you use the "date-time" format instead of "time" 
     *          unless you need to transfer only the time part. 
     * 
     * - email - Internet email address, see RFC 5321, section 4.1.2.
     * 
     * - ipv4 - This should be an ip version 4 address.
     * 
     * - ipv6 - This should be an ip version 6 address.
     * 
     * - hostname - Internet host name, see RFC 1123, section 2.1.
     * 
     * - uuid - A Universally Unique Identifier as defined by RFC 4122. Example: 3e4666bf-d5e5-4aa7-b8ce-cefe41c7568a
     */
    protected array $acceptedTypes = [
        'integer',
        'number',
        'string' =>  ["formats" => ["date-time", "date", "email", "ipv4", "ipv6", "hostname", "uuid", "uri"]],
        'boolean',
        "null",
        "object",
        "array",
    ];

    /**
     * This provides a short description of the instance property.  
     */
    protected function isValidTitle()
    {
        if (!is_string($this->title)) return ['success' => false, 'message' => 'title must be a string value'];
        return ['success' => true];
    }

    /**
     * The value of "multipleOf" MUST be a number, strictly greater than 0.
     * A numeric instance is valid only if division by this keyword's value results in an integer.
     */
    protected function isValidMultipleOf()
    {
        if (!is_numeric($this->multipleOf)) return ['success' => false, 'message' => 'The value of "multipleOf" MUST be a number'];
        if ($this->multipleOf <= 0) return ['success' => false, 'message' => 'The value of "multipleOf" MUST be strictly greater than 0.'];
        return ['success' => true];
    }
    /**
     * This attribute defines the maximum value of the instance property when the type of the instance value is a number.
     */
    protected function isValidMaximum()
    {
        if ($this->maximum && $this->schemaTypeIsNumber())
            return ['success' => false, 'message' => 'maximum property is only enabled with number type'];
        elseif (!is_numeric($this->maximum))
            return ['success' => false, 'message' => 'maximum property should be number type'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minimum, $this->maximum))
            return ['success' => false, 'message' => 'maximum value should be greater than minimum value'];

        return ['success' => true];
    }

    /**
     * This attribute defines the maximum value of the instance property when the type of the instance value is a number.
     */
    protected function isValidMinimum()
    {
        if ($this->minimum && $this->schemaTypeIsNumber())
            return ['success' => false, 'message' => 'minimum property is only valid with number type'];
        elseif (!is_numeric($this->minimum))
            return ['success' => false, 'message' => 'minimum property should be number type'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minimum, $this->maximum))
            return ['success' => false, 'message' => 'minimum value should be smaller than maximun value'];

        return ['success' => true];
    }

    protected function minimunValueIsGreaterThanMaximunValue(int $min, int $max): bool
    {
        if ($max && $min) {
            $minimumIsGreaterThanMaximum = ($min > $max) ? true : false;
            if ($minimumIsGreaterThanMaximum)
                return true;
        }
        return false;
    }

    protected function schemaTypeIsNumber()
    {
        if ($this->type == "integer" || $this->type == "number") return true;
        return false;
    }

    /**
     * if exclusiveMaximum is true, x < maximum.
     * if exclusiveMaximum is false, x ≤ maximum.
     */
    protected function isValidExclusiveMaximum()
    {
        if (!$this->maximum && !is_numeric($this->exclusiveMaximum))
            return ['success' => false, 'message' => 'when maximun value is not defined exclusiveMaximum should be numeric'];
        if ($this->maximum && !is_bool($this->exclusiveMaximum))
            return ['success' => false, 'message' => 'value should be bool type'];

        return ['success' => true];
    }

    /**
     * if exclusiveMinimum is false, x ≥ minimum.
     * if exclusiveMinimum is true, x > minimum.
     */
    protected function isValidExclusiveMinimum()
    {
        if (!$this->minimum && !is_numeric($this->minimum))
            return ['success' => false, 'message' => 'when minimum value is not defined exclusiveMinimum should be numeric'];
        if ($this->minimum && !is_bool($this->exclusiveMinimum))
            return ['success' => false, 'message' => 'value should be bool type'];

        return ['success' => true];
    }

    protected function isValidMaxLength()
    {
        if ($this->type != "string")
            return ['success' => false, 'message' => 'type should be string'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minLength, $this->maxLength))
            return ['success' => false, 'message' => 'maxLength should greater than minLength'];

        if (!$this->isValidLength($this->maxLength))
            return ['success' => false, 'message' => 'maxLength is not valid'];

        return ['success' => true];
    }

    protected function isValidMinLength()
    {
        if ($this->type != "string")
            return ['success' => false, 'message' => 'type should be string'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minLength, $this->maxLength))
            return ['success' => false, 'message' => 'minLength should smaller than maxLength'];

        if (!$this->isValidLength($this->minLength))
            return ['success' => false, 'message' => 'minLength is not valid'];

        return ['success' => true];
    }

    protected function isValidLength($value): bool
    {
        if ($value && is_numeric($value))
            return true;
        return false;
    }

    protected function isValidPattern()
    {
        if (is_string($this->pattern)) return ['success' => true];
        return ['success' => false, 'message' => 'pattern property should be string type'];
    }

    protected function isValidMaxItems()
    {
        if ($this->type != "array")
            return ['success' => false, 'message' => 'type should be array'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minItems, $this->maxItems))
            return ['success' => false, 'message' => 'maxItems should greater than minItems'];

        if (!$this->isValidLength($this->maxItems))
            return ['success' => false, 'message' => 'maxItems is not valid'];

        return ['success' => true];
    }

    protected function isValidMinItems()
    {
        if ($this->type != "array")
            return ['success' => false, 'message' => 'type should be array'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minItems, $this->maxItems))
            return ['success' => false, 'message' => 'minItems should smaller than maxItems'];

        if (!$this->isValidLength($this->minItems))
            return ['success' => false, 'message' => 'minItems is not valid'];

        return ['success' => true];
    }

    protected function isValidUniqueItems()
    {
        if ($this->type != "array")  return ['success' => false, 'message' => 'type should be array'];
        if (!is_bool($this->uniqueItems)) return ['success' => false, 'message' => 'uniqueItems should be a boolean value'];
        return ['success' => true];
    }

    protected function isValidMaxProperties()
    {
        if ($this->type != "object")
            return ['success' => false, 'message' => 'type should be object'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minProperties, $this->maxProperties))
            return ['success' => false, 'message' => 'maxProperties should greater than minProperties'];

        if (!$this->isValidLength($this->maxProperties))
            return ['success' => false, 'message' => 'maxProperties is not valid'];

        return ['success' => true];
    }

    protected function isValidMinProperties()
    {
        if ($this->type != "object")
            return ['success' => false, 'message' => 'type should be object'];

        if ($this->minimunValueIsGreaterThanMaximunValue($this->minProperties, $this->maxProperties))
            return ['success' => false, 'message' => 'minProperties should smaller than maxProperties'];

        if (!$this->isValidLength($this->minProperties))
            return ['success' => false, 'message' => 'minProperties is not valid'];

        return ['success' => true];
    }
    /**
     * The value of this keyword MUST be an array. This array SHOULD have at least one element. Elements in the array SHOULD be unique.
     * An instance validates successfully against this keyword if its value is equal to one of the elements in this keyword's array value.
     * Elements in the array might be of any value, including null.
     */
    protected function isValidEnum()
    {
        if (!is_array($this->enum)) return ['success' => false, 'message' => 'The value of this keyword MUST be an array'];
        if (count($this->enum) < 1) return ['success' => false, 'message' => 'This array SHOULD have at least one element'];
        $optionsInEnum = [];
        foreach ($this->enum as $value) {
            if (in_array($value, $optionsInEnum)) return ['success' => false, 'message' => 'Elements in the array SHOULD be unique'];
            $optionsInEnum[] = $value;
        }
        return ['success' => true];
    }
    /**
     * This keyword's value MUST be a non-empty array. Each item of the array MUST be a valid JSON Schema.
     * An instance validates successfully against this keyword if it validates successfully against all schemas defined by this keyword's value.
     */
    protected function isValidallOf()
    {
        if ($this->multipleOf <= 0) return ['success' => false, 'message' => 'The value of "multipleOf" MUST be a number, strictly greater than 0.'];
        return ['success' => true];
    }

    protected function isValidRequired()
    {
        if ($this->type != "object") return ['success' => false, 'message' => 'Type should be object'];
        if (!is_array($this->required)) return ['success' => false, 'message' => 'The value of this keyword MUST be an array'];
        $elementsRequired = [];
        foreach ($this->required as $value) {
            if (in_array($value, $elementsRequired)) return ['success' => false, 'message' => 'Elements in the array SHOULD be unique'];
        }
        $elementsRequired[] = $value;

        return ['success' => true];
    }
}
