<?php

namespace PonyPanic\Dto;

/**
 * Interface for working with objects postions on the map
 */
interface PositionInterface
{
    public function getPosition(): array;
}