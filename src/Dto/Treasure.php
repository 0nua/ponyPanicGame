<?php

namespace PonyPanic\Dto;

/**
 * Class represented Treasure object
 */
class Treasure implements PositionInterface
{
    /**
     * Treasure id
     * @var int|null
     */
    private ?int $id;

    /**
     * Treasure position
     * @var array|null
     */
    private ?array $position;

    /**
     * Treasure name
     * @var string|null
     */
    private ?string $name;

    /**
     * Collected by Hero id
     * @var int|null
     */
    private ?int $collectedByHeroId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getPosition(): array
    {
        return array_values($this->position);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCollectedByHeroId(): ?int
    {
        return $this->collectedByHeroId;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param array $position
     */
    public function setPosition(array $position): void
    {
        $this->position = $position;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $collectedByHeroId
     */
    public function setCollectedByHeroId(?int $collectedByHeroId): void
    {
        $this->collectedByHeroId = $collectedByHeroId;
    }
}