<?php

namespace ajf\mangl;

class Token
{
    private $type, $typeName;
    private $value;

    const NUMBER = 1,
          IDENTIFIER = 2;

    private static function getTypeNameFromType($type) /* : ?string */ {
        static $constants = NULL;
        if ($constants === NULL) {
            $constants = (new \ReflectionClass(static::class))->getConstants();
        }

        $typeName = array_search($type, $constants);
        if ($typeName === FALSE) {
            return NULL;
        } else {
            return $typeName;
        }
    }

    public function __construct($type, $value = NULL) {
        // integer constants like NUMBER, IDENTIFIER
        if (is_int($type)) {
            $this->typeName = self::getTypeNameFromType($type);
            if ($this->typeName === NULL) {
                throw new \InvalidArgumentException("Unknown token type $type");
            }
        // strings names like ';', '=='
        } else if (is_string($type)) {
            $this->typeName = $type;
        } else {
            throw new \InvalidArgumentException("Invalid token type $type (" . gettype($type) . "), should be integer or string");
        }
        $this->type = $type;
        $this->value = $value;
    }

    public function getType() {
        return $this->type;
    }

    public function getTypeName() {
        return $this->typeName;
    }

    public function getValue() {
        return $this->value;
    }

    public function __debugInfo() {
        $arr = [];
        $arr['type'] = $this->typeName;
        $arr['value'] = $this->value;
        return $arr;
    }

    public function __toString() {
        if (is_string($this->type)) {
            return "\"$this->type\"";
        } else {
            return "$this->typeName \"$this->value\"";
        }
    }
}
