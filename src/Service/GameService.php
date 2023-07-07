<?php

namespace PonyPanic\Service;

use PonyPanic\Client\PonyPanicClient;
use PonyPanic\Dto\Game;
use PonyPanic\Dto\Hero;
use PonyPanic\Dto\Map;
use PonyPanic\Dto\PositionInterface;
use PonyPanic\Dto\Treasure;
use PonyPanic\Enum\BulletDirection;
use PonyPanic\Enum\GameMode;
use PonyPanic\Enum\HeroAction;
use PonyPanic\Hydrator\GameHydrator;
use PonyPanic\Hydrator\MapHydrator;

class GameService
{
    /**
     * Http game client
     *
     * @var PonyPanicClient
     */
    private PonyPanicClient $client;

    /**
     * Game object
     *
     * @var Game
     */
    private Game $game;

    /**
     * Current route to cloeses treasure
     *
     * @var array
     */
    private array $currentRoute = [];

    /**
     * Route service
     *
     * @var RouteService
     */
    private RouteService $route;

    public function __construct()
    {
        $this->client = new PonyPanicClient();
        $this->route  = new RouteService();
    }

    /**
     * Start a story
     *
     * @param bool $continue
     *
     * @return Game
     */
    public function story(bool $continue = false): Game
    {
        $this->game = new Game(GameMode::STORY->value);
        $response   = $continue
            ? $this->client->continue()
            : $this->client->begin();

        $this->game = GameHydrator::hydrate($response, $this->game);

        $map = MapHydrator::hydrate($this->client->mapState());
        if (empty($map->getObstacles())) {
            $obstacles = $this->client->mapResource();
            $map->setObstacles($obstacles);
        }

        $this->game->setCurrentMap($map);

        return $this->game;
    }

    /**
     * Start a freestyle map
     *
     * @param array $params
     *
     * @return Game
     */
    public function freestyle(array $params): Game
    {
        $this->game = new Game(GameMode::FREESTYLE->value);

        $mapToken = $this->client->createMap(...$params);
        $heroId   = $this->client->joinMap($mapToken);

        $this->game->setHeroId($heroId);

        $map = MapHydrator::hydrate($this->client->mapState());
        if (empty($map->getObstacles())) {
            $obstacles = $this->client->mapResource();
            $map->setObstacles($obstacles);
        }

        $this->game->setCurrentMap($map);

        return $this->game;
    }

    /**
     * Returns your hero
     *
     * @return Hero
     */
    private function getMyHero(): Hero
    {
        $key = $this->game->getHeroId() ?? array_key_first($this->game->getCurrentMap()->getHeroes());
        return $this->game->getCurrentMap()->getHeroes()[$key];
    }

    /**
     * Looking for a not collected closest treasure
     *
     * @return Treasure|null
     */
    private function getClosestTreasure(): ?Treasure
    {
        /** @var Treasure $treasure */
        $min     = PHP_INT_MAX;
        $closest = null;
        foreach ($this->game->getCurrentMap()->getTreasures() as $treasure) {
            if ($treasure->getCollectedByHeroId() !== null) {
                continue;
            }

            $moves = $this->getMovesTo($treasure);
            if (count($moves) < $min) {
                $min     = count($moves);
                $closest = $treasure;
            }
        }
        return $closest;
    }

    /**
     * Returns moves array
     *
     * @param PositionInterface $target
     *
     * @return string[]
     */
    private function getMovesTo(PositionInterface $target): array
    {
        $this->route->setMap($this->game->getCurrentMap());
        return $this->route->getMoves($this->getMyHero(), $target);
    }

    /**
     * Calculate next action
     *
     * @return string
     */
    private function getAction(): string
    {
        //Check bullets
        $actions = [];
        [$heroX, $heroY] = $this->getMyHero()->getPosition();
        [$heroNextX, $heroNextY] = HeroAction::getNextPosition($this->getMyHero(), $this->currentRoute[0]);
        foreach ($this->game->getCurrentMap()->getBullets() as $bullet) {
            [$nextX, $nextY] = BulletDirection::getNextPosition($bullet);
            [$x, $y] = $bullet->getPosition();

            if (($x === $heroNextX && $y === $heroNextY) || ($x === $heroX && $y === $heroY)) {
                $actions[HeroAction::USE_SHIELD->name] = max(
                    $actions[HeroAction::USE_SHIELD->name] ?? 0,
                    $bullet->getDamage()
                );
            }

            if ($nextX === $heroNextX && $nextY === $heroNextY) {
                $actions[HeroAction::NOTHING->name] = 0;
            }
        }

        //Check enemies
        foreach ($this->game->getCurrentMap()->getEnemies() as $enemy) {
            if ($enemy->getHealth() <= 0) {
                continue;
            }

            [$x, $y] = $enemy->getPosition();
            $kickAction = HeroAction::convertMoveToKick($this->currentRoute[0]);
            if ($heroNextX === $x && $heroNextY === $y) {
                $actions[$kickAction] = max($actions[$kickAction] ?? 0, $enemy->getDamage());
            }

            if (isset($actions[HeroAction::NOTHING->name]) && $heroX === $x && $heroY === $y) {
                $actions[$kickAction] = max($actions[$kickAction] ?? 0, $enemy->getDamage());
            }

            $neighbours = $this->route->getNeighbours($x, $y);
            foreach ($neighbours as $side => $neighbour) {
                if ($heroNextX === $neighbour[0] && $heroNextY === $neighbour[1]) {
                    if ($x !== $heroX || $y !== $heroY) {
                        $actions[$kickAction] = max($actions[$kickAction] ?? 0, $enemy->getDamage());
                    }
                }

                if (isset($actions[HeroAction::NOTHING->name])) {
                    if ($heroX === $neighbour[0] && $heroY === $neighbour[1]) {
                        $kickAction = HeroAction::getKickFromDirection($side);
                        $actions[$kickAction] = max($actions[$kickAction] ?? 0, $enemy->getDamage());
                    }
                }
            }
        }

        if (!empty($actions)) {
            arsort($actions);
            return array_key_first($actions);
        }

        return array_shift($this->currentRoute);
    }

    /**
     * Makes a next turn and returns logs
     *
     * @return array
     */
    public function makeATurn(): array
    {
        $logs = [];
        if (empty($this->currentRoute)) {
            $treasure           = $this->getClosestTreasure();
            $this->currentRoute = $this->getMovesTo($treasure);
            $logs[]             = sprintf('For the closest treasure %s steps', count($this->currentRoute));
        }

        $action   = $this->getAction();
        $hero     = $this->getMyHero();
        $response = $this->client->approveTurn($hero->getId(), $action);

        MapHydrator::hydrate($this->client->mapState(), $this->game->getCurrentMap());

        $logs[] = sprintf(
            '%s, left %s%% health and %s steps',
            $action,
            $hero->getHealth() * 100,
            count($this->currentRoute)
        );

        return array_merge($logs, $response['tickLogs'] ?? []);
    }

    /**
     * Play next or reset current level
     *
     * @param $reset
     *
     * @return void
     */
    public function nextLevel($reset = false): void
    {
        $response = $reset
            ? $this->client->resetLevel()
            : $this->client->nextLevel();

        GameHydrator::hydrate($response, $this->game);

        /** @var Map $map */
        $map = MapHydrator::hydrate($this->client->mapState());
        if (empty($map->getObstacles())) {
            $obstacles = $this->client->mapResource();
            $map->setObstacles($obstacles);
        }

        $this->game->setCurrentMap($map);

        //Reset current route
        $this->currentRoute = [];
    }
}