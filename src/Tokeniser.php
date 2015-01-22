<?php

namespace ajf\mangl;

class Tokeniser
{
    const SYMBOLS = [
        '++', '--', '+=', '-=', '*=', '/=', '|=', '&=', '^=', '<<', '>>', '&&',
        '||', '^^', '<=', '==', '!=', '>=', '=', ';', ',', ':', '{', '}', '<',
        '>', '=', '|', '&', '^', '+', '-', '*', '/', '!', '~', '.', '[', ']',
        '(', ')'
    ];
    const KEYWORDS = [
        'var', 'if', 'else', 'repeat', 'while', 'do', 'until', 'for', 'switch',
        'case', 'with', 'default', 'break', 'continue', 'exist', 'return',
        'object', 'script', 'const', 'parent', 'create', 'destroy', 'super',
        'div', 'mod'
    ];

    private function isWhitespace(/* string */ $char) /* : bool */ {
        return ($char === ' ' || $char === "\n" || $char === "\r" || $char === "\t");
    }

    private function isDigit(/* string */ $char) /* : bool */ {
        return ('0' <= $char && $char <= '9');
    }

    private function isHexDigit(/* string */ $char) /* : bool */ {
        return ('0' <= $char && $char <= '9')
            || ('a' <= $char && $char <= 'f')
            || ('A' <= $char && $char <= 'F');
    }

    // Don't be scared by the name: it's not a misogynist asshole
    public function isAlpha(/* string */ $char) /* : bool */ {
        return ('a' <= $char && $char <= 'z')
            || ('A' <= $char && $char <= 'Z');
    }

    public function isAlphanumeric(/* string */ $char) /* : bool */ {
        return ('0' <= $char && $char <= '9')
            || ('a' <= $char && $char <= 'z')
            || ('A' <= $char && $char <= 'Z');
    }

    public function tokenise(/* string */ $text) /* : Iterator<Token> */ {
        static $symbols = NULL, $keywords = NULL;
        // cache flipped arrays to use (as sets) for efficient lookup
        if ($symbols === NULL) {
            $symbols = array_flip(self::SYMBOLS);
            $keywords = array_flip(self::KEYWORDS);
        }

        $i = 0;
        $len = strlen($text);

        while ($i < $len) {
            // whitespace is skipped
            if ($this->isWhitespace($text[$i])) {
                $i++;
                continue;
            }

            // numeric literal
            if ($this->isDigit($text[$i])) {
                $digits = $text[$i];
                $i++;
                while ($i < $len && $this->isDigit($text[$i])) {
                    $digits .= $text[$i];
                    $i++;
                }
                yield new Token(TokenType::NUMBER(), (float)$digits);
                continue;
            }

            // hexadecimal numeric literal
            if ($text[$i] === '$') {
                $i++;
                if ($i >= $len) {
                    throw new ParseException("Unexpected EOF when parsing hexadecimal numeric literal");
                }
                if (!$this->isHexDigit($text[$i])) {
                    throw new ParseException("Invalid hexadecimal in numeric literal");
                }
                $digits = '';
                while ($i < $len && $this->isHexDigit($text[$i])) {
                    $digits .= $text[$i];
                    $i++;
                }
                yield new Token(TokenType::NUMBER(), (float)hexdec($digits));
                continue;
            }

            // identifiers
            if ($this->isAlpha($text[$i]) || $text[$i] === '_') {
                $identifier = $text[$i];
                $i++;
                // Note that first char is alpha or _, other chars alphanumeric or _
                while ($i < $len && ($this->isAlphanumeric($text[$i]) || $text[$i] === '_')) {
                    $identifier .= $text[$i];
                    $i++;
                }
                // Keywords like 'global', 'while', 'if'
                if (array_key_exists($identifier, $keywords)) {
                    yield new Token($identifier);
                // Plain old variable/function names
                } else {
                    yield new Token(TokenType::IDENTIFIER(), $identifier);
                }
                continue;
            }

            // 2-character symbols ('==' etc.)
            // We do these first so == isn't parsed as = =
            if ($i + 1 < $len && array_key_exists(substr($text, $i, 2), $symbols)) {
                $symbol = substr($text, $i, 2);
                yield new Token($symbol);
                $i += 2;
                continue;
            }

            // 1-character symbols ('=', ';' etc.)
            if (array_key_exists($text[$i], $symbols)) {
                $symbol = $text[$i];
                yield new Token($symbol);
                $i++;
                continue;
            }

            throw new ParseException("Unexpected character '$text[$i]'");
        }
    }
}
