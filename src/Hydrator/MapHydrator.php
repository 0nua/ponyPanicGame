<?php

namespace PonyPanic\Hydrator;

use PonyPanic\Dto\Map;

class MapHydrator implements HydratorInterface
{
    /**
     * @param array      $data
     * @param mixed|null $object
     *
     * @return mixed
     */
    public static function hydrate(array $data, mixed $object = null): mixed
    {
        if (!$object instanceof Map) {
            $object = new Map();
        }

        $map = $data['map'];

        $treasures = [];
        foreach ($map['treasures'] as $treasure) {
            $id             = $treasure['id'];
            $treasures[$id] = TreasureHydrator::hydrate(
                $treasure,
                $object->getTreasures()[$id] ?? null
            );
        }

        $heroes = [];
        foreach ($data['heroes'] as $hero) {
            $id          = $hero['id'];
            $heroes[$id] = HeroHydrator::hydrate($hero, $object->getHeroes()[$id] ?? null);
        }

        $enemies = [];
        foreach($map['enemies'] as $enemy) {
            $id = $enemy['id'];
            $enemies[$id] = EnemyHydrator::hydrate($enemy, $object->getEnemies()[$id] ?? null);
        }

        $bullets = [];
        foreach ($map['bullets'] as $bullet) {
            $id = $bullet['id'];
            $bullets[$id] = BulletHydrator::hydrate($bullet, $object->getBullets()[$id] ?? null);
        }

        $object->setBullets($bullets);
        $object->setEnemies($enemies);
        $object->setHeroes($heroes);
        $object->setTreasures($treasures);
        $object->setId($map['id']);
        $object->setHeight($map['height']);
        $object->setWidth($map['width']);
        $object->setStatus($map['status']);
        $object->setIsGameOver($map['isGameOver']);

        return $object;
    }
}