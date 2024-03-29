<?php
namespace Models;

class Header extends Parameter
{
    function __construct()
    {
        $this->modelProperties = [];
        $this->requiredProperties = [];
    }

    function isValid(): bool
    {
        if ($this->name) {
            array_push($this->errors, "name MUST NOT be specified, it is given in the corresponding headers map.");
        }
        if ($this->in) {
            array_push($this->errors, "in MUST NOT be specified, it is implicitly in header.");
        }
        return count($this->errors) ? false : true;
    }
}
