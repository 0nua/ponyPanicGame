<?php

namespace PonyPanic\Hydrator;

use PonyPanic\Dto\Enemy;

class EnemyHydrator implements HydratorInterface
{
    /**
     * @param array $data
     * @param mixed $object
     *
     * @return mixed
     */
    public static function hydrate(array $data, mixed $object = null): mixed
    {
        if (!$object instanceof Enemy) {
            $object = new Enemy();
        }

        $object->setId($data['id']);
        $object->setPosition($data['position']);
        $object->setHealth($data['health']);
        $object->setDamage($data['onTouchDamage']);
        $object->setMoveProbability($data['moveProbability']);

        return $object;
    }

}