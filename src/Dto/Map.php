<?php

namespace PonyPanic\Dto;

use PonyPanic\Enums\MapStatus;

/**
 * Class represented Map object
 */
class Map
{
    /**
     * Unique id of the map
     * @var int|null
     */
    private ?int $id;

    /**
     * Width of the map
     * @var int|null
     */
    private ?int $width;

    /**
     * Height of the map
     * @var int|null
     */
    private ?int $height;

    /**
     * Status of the map. Possible values @see MapStatus
     * @var string|null
     */
    private ?string $status;

    /**
     * Array of treasures on the map
     * @var Treasure[]|array
     */
    private array $treasures = [];

    /**
     * Array of enemies on the map
     * @var Enemy[]|array
     */
    private array $enemies = [];

    /**
     * Is game over on the map?
     * @var bool
     */
    private bool $isGameOver = false;

    /**
     * Array of herous on the map
     * @var array|Hero[]
     */
    private array $heroes = [];

    /**
     * Array of the obtacles on the map
     * @var array
     */
    private array $obstacles = [];

    /**
     * Array of bullets on the map
     * @var array|Bullet[]
     */
    private array $bullets = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getTreasures(): array
    {
        return $this->treasures;
    }

    /**
     * @return bool
     */
    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param array $treasures
     */
    public function setTreasures(array $treasures): void
    {
        $this->treasures = $treasures;
    }

    /**
     * @param bool $isGameOver
     */
    public function setIsGameOver(bool $isGameOver): void
    {
        $this->isGameOver = $isGameOver;
    }

    /**
     * @param array $heroes
     */
    public function setHeroes(array $heroes): void
    {
        $this->heroes = $heroes;
    }

    /**
     * @return array
     */
    public function getHeroes(): array
    {
        return $this->heroes;
    }

    /**
     * @return array
     */
    public function getObstacles(): array
    {
        return $this->obstacles;
    }

    /**
     * @param array $obstacles
     */
    public function setObstacles(array $obstacles): void
    {
        $this->obstacles = $obstacles;
    }

    /**
     * @return Enemy[]
     */
    public function getEnemies(): array
    {
        return $this->enemies;
    }

    /**
     * @param array $enemies
     */
    public function setEnemies(array $enemies): void
    {
        $this->enemies = $enemies;
    }

    /**
     * @return Bullet[]
     */
    public function getBullets(): array
    {
        return $this->bullets;
    }

    /**
     * @param array $bullets
     */
    public function setBullets(array $bullets): void
    {
        $this->bullets = $bullets;
    }
}