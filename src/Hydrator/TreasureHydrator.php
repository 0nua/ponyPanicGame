<?php

namespace PonyPanic\Hydrator;

use PonyPanic\Dto\Treasure;

class TreasureHydrator implements HydratorInterface
{

    public static function hydrate(array $data, mixed $object = null): mixed
    {
        if (!$object instanceof Treasure) {
            $object = new Treasure();
        }

        $object->setId($data['id']);
        $object->setName($data['name']);
        $object->setPosition($data['position']);
        $object->setCollectedByHeroId($data['collectedByHeroId']);

        return $object;
    }
}