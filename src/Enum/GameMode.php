<?php

namespace PonyPanic\Enum;

/**
 * Possible game modes
 */
enum GameMode: string
{
    case FREESTYLE = 'freestyle';
    case STORY = 'story';

    /**
     * @param string $mode
     *
     * @return bool
     */
    public static function isStory(string $mode): bool
    {
        return $mode === self::STORY->value;
    }

    /**
     * @param string $mode
     *
     * @return bool
     */
    public static function isFreestyle(string $mode): bool
    {
        return $mode === self::FREESTYLE->value;
    }
}