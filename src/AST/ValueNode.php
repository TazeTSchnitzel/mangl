<?php

namespace ajf\mangl\AST;

class ValueNode extends Node
{
    private $value;

    public function __construct(NodeType $type, $value) {
        parent::__construct($type);
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function __debugInfo() {
        $arr = parent::__debugInfo();
        $arr['value'] = $this->value;
        return $arr;
    }
}
