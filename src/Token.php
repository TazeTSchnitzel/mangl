<?php

namespace ajf\mangl;

class Token
{
    private $type;
    private $value;

    public function __construct($type, $value = NULL) {
        // $type is either a TokenType enum value (NUMBER, IDENTIFIER etc.)
        // or, $type is a string name (';', '==' etc.)
        if (!$type instanceof TokenType && !is_string($type)) {
            throw new \InvalidArgumentException("Invalid token type $type (" . gettype($type) . "), should be TokenType or string");
        }
        $this->type = $type;
        $this->value = $value;
    }

    public function getType() {
        return $this->type;
    }

    public function getValue() {
        return $this->value;
    }

    public function __debugInfo() {
        $arr = [];
        $arr['type'] = $this->type;
        $arr['value'] = $this->value;
        return $arr;
    }

    public function __toString() {
        if (is_string($this->type)) {
            return "\"$this->type\"";
        } else {
            return "$this->type \"$this->value\"";
        }
    }
}
