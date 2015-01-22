<?php

namespace ajf\mangl\AST;

class ListNode extends Node
{
    private $children;

    public function __construct(NodeType $type, Node ...$children) {
        parent::__construct($type);
        $this->children = $children;
    }

    public function getChildren() {
        return $this->children;
    }

    public function __debugInfo() {
        $arr = parent::__debugInfo();
        $arr['children'] = $this->children;
        return $arr;
    }
}
