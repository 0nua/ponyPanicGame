<?php

namespace PonyPanic\Dto;

use PonyPanic\Enum\MapStatus;

/**
 * Class represented Game object
 */
class Game
{
    /**
     * Current level
     * @var int
     */
    private int $currentLevel = 0;

    /**
     * Current map object
     * @var Map|null
     */
    private ?Map $currentMap;

    /**
     * Is current level finished flag
     * @var bool
     */
    private bool $isCurrentLevelFinished = false;

    /**
     * Story text for the current map
     * @var string|null
     */
    private ?string $story;

    /**
     * Current game mode.
     * @var string
     */
    private string $mode;

    private ?int $heroId = null;

    /**
     * @param string $mode
     *
     * @return void
     */
    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return int|null
     */
    public function getCurrentLevel(): ?int
    {
        return $this->currentLevel;
    }

    /**
     * @param int|null $currentLevel
     */
    public function setCurrentLevel(?int $currentLevel): void
    {
        $this->currentLevel = $currentLevel;
    }

    /**
     * @return Map|null
     */
    public function getCurrentMap(): ?Map
    {
        return $this->currentMap;
    }

    /**
     * @param Map|null $currentMap
     */
    public function setCurrentMap(?Map $currentMap): void
    {
        $this->currentMap = $currentMap;
    }

    /**
     * @return bool
     */
    public function isCurrentLevelFinished(): bool
    {
        return $this->isCurrentLevelFinished;
    }

    /**
     * @param bool $isCurrentLevelFinished
     */
    public function setIsCurrentLevelFinished(bool $isCurrentLevelFinished): void
    {
        $this->isCurrentLevelFinished = $isCurrentLevelFinished;
    }

    /**
     * @return string|null
     */
    public function getStory(): ?string
    {
        return $this->story;
    }

    /**
     * @param string|null $story
     */
    public function setStory(?string $story): void
    {
        $this->story = $story;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param int $heroId
     */
    public function setHeroId(int $heroId): void
    {
        $this->heroId = $heroId;
    }

    /**
     * @return int|null
     */
    public function getHeroId(): ?int
    {
        return $this->heroId;
    }

    /**
     * @return bool
     */
    public function isMapWon(): bool
    {
        return MapStatus::isWon($this->currentMap);
    }

    public function isMapLost(): bool
    {
        return MapStatus::isLost($this->currentMap);
    }
}