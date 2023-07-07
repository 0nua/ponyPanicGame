<?php

namespace PonyPanic\Dto;

/**
 * Class represented Enemy object
 */
class Enemy implements PositionInterface
{
    /**
     * Enemy id
     * @var int|null
     */
    private ?int $id;

    /**
     * Enemy current position [x,y]
     * @var array|null
     */
    private ?array $position = [];

    /**
     * Enemy current health
     * @var float|null
     */
    private ?float $health;

    /**
     * Enemy possible damage
     * @var float|null
     */
    private ?float $damage;

    /**
     * Enemy moving probability
     * @var float|null
     */
    private ?float $moveProbability;

    /**
     * @return int
     */
    public function getId(): int
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

    public function getPosition(): array
    {
        return array_values($this->position);
    }

    /**
     * @param array|null $position
     */
    public function setPosition(array $position): void
    {
        $this->position = $position;
    }

    /**
     * @return float|null
     */
    public function getHealth(): ?float
    {
        return $this->health;
    }

    /**
     * @param float|null $health
     */
    public function setHealth(float $health): void
    {
        $this->health = $health;
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

    /**
     * @return float|null
     */
    public function getMoveProbability(): ?float
    {
        return $this->moveProbability;
    }

    /**
     * @param float|null $moveProbability
     */
    public function setMoveProbability(float $moveProbability): void
    {
        $this->moveProbability = $moveProbability;
    }
}