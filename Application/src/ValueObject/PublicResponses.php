<?php

namespace Application\ValueObject;

class PublicResponses
{

    private static array $responses = [
        "там" => "(помирає)"
    ];
    public static function getResponse(string $key): string {
        return self::$responses[$key];
    }
    public static function hasResponse(string $key): bool {
        return array_key_exists($key, self::$responses);
    }
}