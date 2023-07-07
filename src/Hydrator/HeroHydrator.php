<?php

namespace PonyPanic\Hydrator;

use PonyPanic\Dto\Hero;

class HeroHydrator implements HydratorInterface
{

    public static function hydrate(array $data, mixed $object = null): mixed
    {
        if (!$object instanceof Hero) {
            $object = new Hero();
        }

        $object->setId($data['id']);
        $object->setPosition($data['position']);
        $object->setHealth($data['health']);
        $object->setPlayerId($data['playerId']);
        $object->setScore($data['score']);

        return $object;
    }
}