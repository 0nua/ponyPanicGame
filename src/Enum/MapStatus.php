<?php

namespace PonyPanic\Enum;

use \PonyPanic\Dto\Map;

/**
 * Possible values of the map statuses
 */
enum MapStatus {

    case LOST;
    case WON;

    /**
     * @param Map $map
     *
     * @return bool
     */
    public static function isWon(Map $map): bool
    {
        return $map->getStatus() === self::WON->name;
    }

    /**
     * @param Map $map
     *
     * @return bool
     */
    public static function isLost(Map $map): bool
    {
        return $map->getStatus() === self::LOST->name;
    }
}