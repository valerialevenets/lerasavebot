<?php

namespace Application\ValueObject;

class AlcoholResponses
{
    private static array $responses = [
        'Знову нажерся?',
        'Знову напився?',
        'Знову бухаєш?',
        'Харе бухати',
        'Фуууу, алкота'
    ];
    public static function getRandomResponse(): string
    {
        return self::$responses[array_rand(self::$responses)];
    }
}