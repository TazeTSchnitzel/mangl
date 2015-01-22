<?php

namespace ajf\mangl;

// Because every other Enum class I've seen just isn't quite good enough
// https://github.com/myclabs/php-enum is alright but it has its problems
// Thus I'm implementing my own *sigh*
abstract class Enum
{
    private $name;

    // cannot be instantiated directly
    private function __construct($name) {
        $this->name = $name;
    }

    public function __toString() {
        return static::class . "::" . $this->name;
    }

    public function __debugInfo() {
        return [
            "name" => $this->name
        ];
    }

    public function getName() {
        return $this->name;
    }

    private static $valuesCache = [], $declCache = [];

    public static function __callStatic($name, $arguments) {
        if (!isset(self::$declCache[static::class])) {
            self::$declCache[static::class] = array_flip(static::members);
        }
        if (!isset(self::$declCache[static::class][$name])) {
            throw new \RuntimeException("Unknown enum member " . static::class . "::" . $name);
        }
        if (!isset(self::$valuesCache[static::class][$name])) {
            self::$valuesCache[static::class][$name] = new static($name);
        }
        return self::$valuesCache[static::class][$name];
    }
}
