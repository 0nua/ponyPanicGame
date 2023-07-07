<?php

namespace PonyPanic\Service;

use PonyPanic\Dto\Map;
use PonyPanic\Dto\PositionInterface;
use PonyPanic\Enum\HeroAction;

/**
 * Class for working with map and calculating fastest route
 */
class RouteService
{
    /**
     * @var Map
     */
    private Map $map;

    /**
     * Calculates fastest route from $start to $end and returns array of movies
     *
     * @param PositionInterface $start
     * @param PositionInterface $end
     *
     * @return string[]
     *@see HeroAction
     *
     */
    public function getMoves(PositionInterface $start, PositionInterface $end): array
    {
        $route = $this->getMinRoute($start, $end);
        $moves = $this->convertRouteToMoves($route);

        return $moves;
    }

    /**
     * Returns Hero moves instead of route points
     * @param array $route
     *
     * @return array
     */
    private function convertRouteToMoves(array $route): array
    {
        [$x, $y] = array_shift($route);

        $moves = [];
        foreach ($route as $point) {
            if ($point[0] !== $x) {
                $moves[] = $point[0] > $x ? HeroAction::MOVE_RIGHT->name : HeroAction::MOVE_LEFT->name;
            } else {
                $moves[] = $point[1] > $y ? HeroAction::MOVE_UP->name : HeroAction::MOVE_DOWN->name;
            }

            [$x, $y] = $point;
        }

        return $moves;
    }

    /**
     * Get min available route
     *
     * @param PositionInterface $start
     * @param PositionInterface $end
     *
     * @return array[]
     */
    private function getMinRoute(PositionInterface $start, PositionInterface $end): array
    {
        $matrix = $this->getMatrix($start, $end);

        //For route calc we should go in reverse way
        [$x, $y] = $end->getPosition();

        $route = [$end->getPosition()];

        $length = $matrix[$x][$y];
        while ($length > 0) {
            $neigbours = $this->getNeighbours($x, $y);

            $min       = PHP_INT_MAX;
            $nextPoint = null;

            foreach ($neigbours as $neigbour) {
                [$i, $j] = $neigbour;
                if (isset($matrix[$i][$j]) && $matrix[$i][$j] < $min) {
                    $min       = $matrix[$i][$j];
                    $nextPoint = $neigbour;
                }
            }

            //Use stack
            array_unshift($route, $nextPoint);

            $length = $min;
            [$x, $y] = $nextPoint;
        }

        return $route;
    }

    /**
     * Init map as matrix of steps for waves algo
     * @param PositionInterface $start
     * @param                   $end
     *
     * @return array|\int[][]
     */
    private function getMatrix(PositionInterface $start, PositionInterface $end): array
    {
        [$x, $y] = $start->getPosition();
        $matrix = [
            $x => [
                $y => 0,
            ],
        ];

        $obstacles = $this->map->getObstacles();
        foreach ($obstacles as $i => $points) {
            foreach ($points as $j) {
                $matrix[$i][$j] = PHP_INT_MAX;
            }
        }

        $queue = [[$x, $y]];
        while (count($queue) > 0) {
            $point     = array_pop($queue);
            $neighbors = $this->getNeighbours($point[0], $point[1]);
            foreach ($neighbors as $neighbor) {
                [$i, $j] = $neighbor;
                if (isset($matrix[$i][$j])) {
                    continue;
                }

                if ($i < 0 || $i >= $this->map->getWidth()) {
                    continue;
                }

                if ($j < 0 || $j >= $this->map->getHeight()) {
                    continue;
                }

                $matrix[$i][$j] = $matrix[$point[0]][$point[1]] + 1;
                if ($i === $end->getPosition()[0] && $j === $end->getPosition()[1]) {
                    break 2;
                }

                array_unshift($queue, [$i, $j]);
            }
        }

        return $matrix;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return array[]
     */
    public function getNeighbours(int $x, int $y): array
    {
        return [
            'DOWN'  => [$x, $y - 1],
            'UP'    => [$x, $y + 1],
            'RIGHT' => [$x + 1, $y],
            'LEFT'  => [$x - 1, $y],
        ];
    }

    /**
     * @param Map $map
     */
    public function setMap(Map $map): void
    {
        $this->map = $map;
    }
}