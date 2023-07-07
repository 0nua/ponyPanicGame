<?php

namespace PonyPanic\Hydrator;

use PonyPanic\Dto\Bullet;

class BulletHydrator implements HydratorInterface
{
    /**
     * @param array      $data
     * @param mixed|null $object
     *
     * @return mixed
     */
    public static function hydrate(array $data, mixed $object = null): mixed
    {
        if (!$object instanceof Bullet) {
            $object = new Bullet();
        }

        $object->setId($data['id']);
        $object->setDirection($data['direction']);
        $object->setPosition($data['position']);
        $object->setDamage($data['damage']);

        return $object;
    }
}