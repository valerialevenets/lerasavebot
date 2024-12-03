<?php

namespace Application\ValueObject;
class SerhiiResponses
{
    public static function getResponses(): array
    {
        return [
            'Ага, тримай рота ширше',
            "Пустив Живчика тобі на капелюх, ознайомся",
            'Ти шо в сосну в\'їбався ?',
            'Знову нажерся?',
            'Знову напився?',
            'Знову бухаєш?',
            'Насцяв тобі на лице'
        ];
    }

    public static function getRandomResponse(): string
    {
        $responses = self::getResponses();
        return $responses[array_rand($responses)];
    }
}
