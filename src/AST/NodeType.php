<?php

namespace ajf\mangl\AST;

use ajf\mangl\Enum;

class NodeType extends Enum
{
    const members = [
        'IDENTIFIER',
        'VAR_DECL',
        'STATEMENTS'
    ];
}
