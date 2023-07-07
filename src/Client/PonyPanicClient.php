<?php

namespace PonyPanic\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Client class for working with Pony Pamic game api
 */
class PonyPanicClient
{
    private const ENDPOINT_STORY_BEGIN = 'story/begin';

    private const ENDPOINT_RESET_LEVEL = 'story/resetLevel';

    private const ENDPOINT_NEXT_LEVEL = 'story/nextLevel';

    private const ENDPOINT_MAP_RESOURCE = 'play/mapResource';

    private const ENDPOINT_APPROVE_HERO_TURN = 'play/approveHeroTurn';

    private const ENDPOINT_MAP_STATE = 'play/mapState';

    private const ENDPOINT_JOIN_MAP = 'freestyle/joinMap';

    private const ENDPOINT_CREATE_MAP = 'freestyle/createMap';

    private Client $client;

    /**
     * Pony Panic Player token
     * @var string
     */
    private string $playerToken;

    /**
     * Pony Panic Story token
     * @var string|null
     */
    private ?string $storyToken = null;

    /**
     * Pony Panic map token
     * @var string|null
     */
    private ?string $mapToken = null;

    public function __construct()
    {
        $this->client      = new Client(
            [
                'base_uri'              => $_ENV['BASE_URI'],
                'timeout'               => 15,
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
        $this->playerToken = $_ENV['PLAYER_TOKEN'];
    }

    /**
     * Begin the story
     *
     * POST /playGameApi/v1/story/begin
     *
     * @return array
     */
    public function begin(): array
    {
        $response         = $this->request(self::ENDPOINT_STORY_BEGIN, 'POST');
        $this->storyToken = $this->processResponse($response, self::ENDPOINT_STORY_BEGIN, 'storyPlaythroughToken');

        file_put_contents(PROJECT_DIR . '/data/game.txt', $this->storyToken);

        return $this->processResponse($response, self::ENDPOINT_STORY_BEGIN, 'playthroughState');
    }

    /**
     * Gets the story token from a file and reset the last level
     *
     * @return array
     */
    public function continue(): array
    {
        $this->storyToken = file_get_contents(PROJECT_DIR . '/data/game.txt');

        return $this->resetLevel();
    }

    /**
     * Create a map with params
     *
     * POST /playGameApi/v1/freestyle/createMap
     *
     * @param int $height
     * @param int $width
     * @param int $treasureCount
     *
     * @return string
     */
    public function createMap(int $height, int $width, int $treasureCount): string
    {
        $response = $this->request(self::ENDPOINT_CREATE_MAP, 'POST', [
            'height'           => $height,
            'width'            => $width,
            'treasureCount'    => $treasureCount,
            'obstaclesPresent' => true,
            //'enemyConfig'      => [],
        ]);

        return $this->processResponse(
            $response,
            self::ENDPOINT_CREATE_MAP,
            'mapToken'
        );
    }

    /**
     * Join to the created map
     *
     * POST /playGameApi/v1/freestyle/joinMap
     *
     * @param string $mapToken
     *
     * @return int
     */
    public function joinMap(string $mapToken): int
    {
        $this->mapToken = $mapToken;
        return (int)$this->processResponse(
            $this->request(self::ENDPOINT_JOIN_MAP, 'POST'),
            self::ENDPOINT_JOIN_MAP,
            'heroId'
        );
    }

    /**
     * Returns a state of the current map
     *
     * GET /playGameApi/v1/play/mapState
     *
     * @return array
     */
    public function mapState(): array
    {
        return $this->processResponse(
            $this->request(self::ENDPOINT_MAP_STATE),
            self::ENDPOINT_MAP_STATE
        );
    }

    /**
     * Approve a turn with a deteminated action
     *
     * POST /playGameApi/v1/play/approveHeroTurn
     *
     * @param int    $heroId
     * @param string $action
     *
     * @return array
     */
    public function approveTurn(int $heroId, string $action): array
    {
        $response = $this->request(
            self::ENDPOINT_APPROVE_HERO_TURN,
            'POST',
            [
                'heroId' => $heroId,
                'action' => $action,
            ]
        );

        return $this->processResponse($response, self::ENDPOINT_APPROVE_HERO_TURN);
    }

    /**
     * Returns resources of the current map
     *
     * GET /playGameApi/v1/play/mapResource
     *
     * @return array
     * @throws GuzzleException
     */
    public function mapResource(): array
    {
        return $this->processResponse(
            $this->request(self::ENDPOINT_MAP_RESOURCE),
            self::ENDPOINT_MAP_RESOURCE,
            'compressedObstacles.coordinateMap'
        );
    }

    /**
     * Go to the next level
     *
     * POST /playGameApi/v1/story/nextLevel
     *
     * @return array
     */
    public function nextLevel(): array
    {
        return $this->processResponse(
            $this->request(self::ENDPOINT_NEXT_LEVEL, 'POST'),
            self::ENDPOINT_NEXT_LEVEL,
            'playthroughState'
        );
    }

    /**
     * Reset the current level
     *
     * POST /playGameApi/v1/story/resetLevel
     *
     * @return array
     */
    public function resetLevel(): array
    {
        return $this->processResponse(
            $this->request(self::ENDPOINT_RESET_LEVEL, 'POST'),
            self::ENDPOINT_RESET_LEVEL,
            'playthroughState'
        );
    }

    /**
     * Process a response
     *
     * @param array  $response
     * @param string $key
     * @param string $endpoint
     *
     * @return array
     */
    private function processResponse(array $response, string $endpoint, string $key = null): mixed
    {
        $result = $response;

        if ($key !== null) {
            $keys = explode('.', $key);
            foreach ($keys as $key) {
                $result = $result[$key] ?? null;
            }
        }

        if ($result === null) {
            throw new \LogicException(
                sprintf('Invalid response from %s endpoint. Key %s not exists in response', $endpoint, $key)
            );
        }

        return $result;
    }

    /**
     * @param string $endpoint - particular game endpoint name
     * @param string $method   - http method GET/POST/PUT/PATCH/DELETE
     * @param array  $body     - request body content, will be converted to json automatically
     * @param bool   $repeat   - count of repeated requests
     *
     * @return array
     * @throws \RuntimeException
     */
    private function request(string $endpoint, string $method = 'GET', array $body = [], int $repeat = 1): array
    {
        try {
            $options = [
                RequestOptions::HEADERS => $this->auth(),
            ];

            if ($method === 'POST') {
                $options['json'] = $body;
            }

            $response = $this->client->request($method, $endpoint, $options);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response;
        } catch (\Throwable $exception) {
            if ($repeat <= 5) {
                sleep(0.2 * $repeat);
                return $this->request($endpoint, $method, $body, ++$repeat);
            }

            $message = $exception->getMessage();
            if ($exception instanceof ClientException) {
                $message = $exception->getResponse()->getBody()->getContents();
            }

            throw new \RuntimeException($message);
        }
    }

    /**
     * Determine corrent authorization headers
     *
     * @return array
     */
    private function auth(): array
    {
        if ($this->mapToken) {
            return [
                'Map-Token'    => $this->mapToken,
                'Player-Token' => $this->playerToken,
            ];
        }

        if ($this->storyToken == null) {
            return ['Player-Token' => $this->playerToken];
        }

        return ['Story-Playthrough-Token' => $this->storyToken];
    }
}