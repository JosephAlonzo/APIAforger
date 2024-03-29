<?php
namespace Models;

class Xml extends Base
{
    /**
     * Replaces the name of the element/attribute used for the described schema property. 
     * When defined within items, it will affect the name of the individual XML elements within the list. 
     * When defined alongside type being array (outside the items), it will affect the wrapping element and only if wrapped is true. 
     * If wrapped is false, it will be ignored.
     */
    public string $name;
    /**
     * The URI of the namespace definition. Value MUST be in the form of an absolute URI.
     */
    public string $namespace;
    /**
     * The prefix to be used for the name.
     */
    public string $prefix;
    /**
     * Declares whether the property definition translates to an attribute instead of an element. Default value is false.
     */
    public bool $attribute;
    /**
     * MAY be used only for an array definition. 
     * Signifies whether the array is wrapped (for example, <books><book/><book/></books>) or unwrapped (<book/><book/>). 
     * Default value is false. 
     * The definition takes effect only when defined alongside type being array (outside the items).
     */
    public bool $wrapped;
}
