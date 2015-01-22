<?php

namespace ajf\mangl;

class Parser
{
    public function parse(\Iterator $tokens) {
        $tokens->rewind();

        return $this->parseStatements($tokens);
    }

    private function parseStatements(\Iterator $tokens) {
        $stmts = [];

        while ($tokens->valid()) {
            $stmts[] = $this->parseStatement($tokens);
        }

        return new AST\ListNode(AST\NodeType::STATEMENTS(), ...$stmts);
    }

    private function parseStatement(\Iterator $tokens) {
        $token = $tokens->current();

        // var declarations
        if ($token->getType() === 'var') {
            $tokens->next();
            $token = $tokens->current();
            $vars = [];
            while(true) {
                if ($token->getType() !== TokenType::IDENTIFIER()) {
                    throw new ParseException("Unexpected " . $tokens->current()->getType() . " when parsing var statement, variable name expected");
                }
                $vars[] = new AST\ValueNode(AST\NodeType::IDENTIFIER(), $token->getValue());
                $tokens->next();

                if (!$tokens->valid()) {
                    throw new ParseException("Unexpected EOF when parsing var statement");
                }
                $token = $tokens->current();

                // var name can only be followed by ',' or ';'
                if ($token->getType() === ',') {
                    $tokens->next();
                    $token = $tokens->current();
                    continue;
                } else if ($token->getType() === ';') {
                    $tokens->next();
                    break;
                } else {
                    throw new ParseException("Unexpected " . $token->getType() . " when parsing var statement, ',' or ';' expected");
                }
            }
            return new AST\ListNode(AST\NodeType::VAR_DECL(), ...$vars);
        }

        throw new ParseException("Unexpected token " . $token->getType() . " when parsing statement");
    }
}
