<?php


namespace App\Service;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleHttpService
{
    /**
     * @var Client $client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    const REQUEST_METHOD_POST = "POST";

    const KEY_JSON = "json";

    /**
     * @param string $calledUrl
     * @param array $dataBag
     * @return string
     * @throws GuzzleException
     */
    public function sendPostRequest(string $calledUrl, array $dataBag = []): string
    {
        $response = $this->client->request(self::REQUEST_METHOD_POST, $calledUrl, [
            self::KEY_JSON => $dataBag,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @param string $calledUrl
     * @return string
     * @throws GuzzleException
     */
    public function sendGetRequest(string $calledUrl): string
    {
        $response = $this->client->get($calledUrl);
        return $response->getBody()->getContents();
    }
}