<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class WareHouseClientService
{
    /** @var string */
    protected $endpoint;

    protected $client;

    const TIMEOUT = 15;

    const CONNECT_TIMEOUT = 5;

    /**
     * PoBoxPurchaseService constructor.
     */
    public function __construct()
    {
        //$this->endpoint = 'http://192.168.16.1:8081';
        //$this->endpoint = 'http://warehouse_nginx';
       // $this->endpoint = env('WAREHOUSE_HOST', 'http://warehouse_nginx');
        $this->endpoint = env('WAREHOUSE_HOST', 'http://localhost:8081');
        $this->client = $this->createClient();
    }

    /**
     * @return GuzzleHttpClient
     */
    private function createClient(): GuzzleHttpClient
    {
        return new GuzzleHttpClient([
            'base_uri' => $this->endpoint,
            'headers'  => ['Content-Type' => 'application/json'],
            'verify'   => false,
            'timeout' => self::TIMEOUT,
            'connect_timeout' => self::CONNECT_TIMEOUT
        ]);
    }

    /**
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function getAllIngredients($page, $pageSize)
    {
        try {
            /** @var Response $response */
            $response = $this->client->get('api/warehouse/v1/ingredients', [
                'query' => [
                    'page' => $page,
                    'pageSize' => $pageSize,
                ],
            ]);
        } catch (Exception $e) {
            logger($e->getMessage());
            logger($e->getTraceAsString());
            throw new Exception($e->getMessage());
        }
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function getAllPurchases($page, $pageSize)
    {
        try {
            /** @var Response $response */
            $response = $this->client->post('api/warehouse/v1/purchases', [
                'json' => [
                    'page' => $page,
                    'pageSize' => $pageSize
                ],
            ]);
        } catch (Exception $e) {
            logger($e->getMessage());
            logger($e->getTraceAsString());
            throw new Exception($e->getMessage());
        }
        return json_decode($response->getBody()->getContents());
    }
}
