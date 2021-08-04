<?php


namespace ProjectZero4\RiotApi;


use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 * @package ProjectZero4\RiotApi
 */
class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected \GuzzleHttp\Client $client;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'headers' => [
                "Accept-Charset" => "application/x-www-form-urlencoded; charset=UTF-8",
                "X-Riot-Token" => env('RIOT_GAMES_API_KEY')
            ]
        ]);
    }

    /**
     * @param string $uri
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function get(string $uri): ResponseInterface
    {
        return $this->client->get($uri);
    }

    /**
     * @param string $uri
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function post(string $uri): ResponseInterface
    {
        return $this->client->post($uri);

    }

    /**
     * @param string $uri
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function patch(string $uri): ResponseInterface
    {
        return $this->client->patch($uri);

    }

    /**
     * @param string $uri
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function put(string $uri): ResponseInterface
    {
        return $this->client->put($uri);

    }

    /**
     * @param string $uri
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function delete(string $uri): ResponseInterface
    {
        return $this->client->delete($uri);

    }
}
