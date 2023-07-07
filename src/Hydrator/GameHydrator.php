<?php

namespace PonyPanic\Hydrator;

use PonyPanic\Dto\Game;

class GameHydrator implements HydratorInterface
{

    public static function hydrate(array $data, mixed $object = null): mixed
    {
        if (!$object instanceof Game) {
            $object = new Game();
        }

        $object->setCurrentLevel($data['currentLevel']);
        $object->setIsCurrentLevelFinished($data['isCurrentLevelFinished']);
        $object->setStory($data['storyLine']);

        return $object;
    }
}