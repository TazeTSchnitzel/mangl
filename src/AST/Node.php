<?php

namespace ajf\mangl\AST;

abstract class Node
{
    private /* NodeType */ $type;

    public function __construct(NodeType $type) {
        $this->type = $type;
    }

    public function getType() /* : NodeType */ {
        return $this->type;
    }

    public function __debugInfo() {
        return [
            'type' => $this->type->getName()
        ];
    }
}
