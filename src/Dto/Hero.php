<?php

namespace PonyPanic\Dto;

/**
 * Class represented Hero object
 */
class Hero implements PositionInterface
{
    /**
     * Hero id
     * @var int|null
     */
    private ?int $id;

    /**
     * Current player id from player token
     * @var int|null
     */
    private ?int $playerId;

    /**
     * Hero position [x, y]
     * @var array|null
     */
    private ?array $position;

    /**
     * Hero health
     * @var float|null
     */
    private ?float $health;

    /**
     * Hero's score
     * @var float|null
     */
    private ?float $score;

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
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @return array
     */
    public function getPosition(): array
    {
        return array_values($this->position);
    }

    /**
     * @return float
     */
    public function getHealth(): float
    {
        return $this->health;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int|null $playerId
     */
    public function setPlayerId(?int $playerId): void
    {
        $this->playerId = $playerId;
    }

    /**
     * @param array|null $position
     */
    public function setPosition(?array $position): void
    {
        $this->position = $position;
    }

    /**
     * @param float|null $health
     */
    public function setHealth(?float $health): void
    {
        $this->health = $health;
    }

    /**
     * @param float|null $score
     */
    public function setScore(?float $score): void
    {
        $this->score = $score;
    }
}