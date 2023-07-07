<?php

namespace PonyPanic\Dto;

/**
 * Class represented Bullet object
 */
class Bullet implements PositionInterface
{
    /**
     * Bullet id
     * @var int|null
     */
    private ?int $id;

    /**
     * Bullet direction
     * @var string|null
     */
    private ?string $direction;

    /**
     * Bullet current position [x, y]
     * @var array|null
     */
    private ?array $position = [];

    /**
     * Bullet possible damage
     * @var float|null
     */
    private ?float $damage;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }

    /**
     * @return array
     */
    public function getPosition(): array
    {
        return array_values($this->position);
    }

    /**
     * @param array $position
     */
    public function setPosition(array $position): void
    {
        $this->position = $position;
    }

    /**
     * @return float|null
     */
    public function getDamage(): ?float
    {
        return $this->damage;
    }

    /**
     * @param float|null $damage
     */
    public function setDamage(float $damage): void
    {
        $this->damage = $damage;
    }
}