<?php

namespace PonyPanic\Enum;

use PonyPanic\Dto\Bullet;

/**
 * Possible bullet directions
 */
enum BulletDirection
{
    case LEFT;
    case RIGHT;
    case UP;
    case DOWN;

    /**
     * @param Bullet $bullet
     * @param string            $move
     *
     * @return array
     */
    public static function getNextPosition(Bullet $bullet): array
    {
        [$x, $y] = $bullet->getPosition();

        switch ($bullet->getDirection()) {
            case self::LEFT->name:
                return [$x - 1, $y];
            case self::RIGHT->name:
                return [$x + 1, $y];
            case self::UP->name:
                return [$x, $y + 1];
            case self::DOWN->name:
                return [$x, $y - 1];
            default:
                throw new \LogicException('Unknown move');
        }
    }
}