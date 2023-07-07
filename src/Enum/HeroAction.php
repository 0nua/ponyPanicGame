<?php

namespace PonyPanic\Enum;

use PonyPanic\Dto\Hero;

/**
 * Possible Hero actions
 */
enum HeroAction
{
    case MOVE_LEFT;
    case MOVE_RIGHT;
    case MOVE_UP;
    case MOVE_DOWN;

    case KICK_LEFT;
    case KICK_RIGHT;
    case KICK_UP;
    case KICK_DOWN;

    case NOTHING;

    case USE_SHIELD;

    /**
     * @param Hero $object
     * @param string            $move
     *
     * @return array
     */
    public static function getNextPosition(Hero $hero, string $move): array
    {
        [$x, $y] = $hero->getPosition();

        switch ($move) {
            case self::MOVE_LEFT->name:
                return [$x - 1, $y];
            case self::MOVE_RIGHT->name:
                return [$x + 1, $y];
            case self::MOVE_UP->name:
                return [$x, $y + 1];
            case self::MOVE_DOWN->name:
                return [$x, $y - 1];
            default:
                throw new \LogicException('Unknown move');
        }
    }

    /**
     * @param string $move
     *
     * @return string
     */
    public static function convertMoveToKick(string $move): string
    {
        return str_replace('MOVE', 'KICK', $move);
    }

    /**
     * @param string $move
     *
     * @return string
     */
    public static function getKickFromDirection(string $direction): string
    {
        return sprintf('KICK_%s', $direction);
    }
}