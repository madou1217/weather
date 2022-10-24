<?php

namespace Madou1217\Weather;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Madou1217\Weather\Exceptions\HttpException;

class Weather
{
    private string $base_url;
    private Client $client;
    /**
     * 高德开放平台 应用 Key
     * @var string
     */
    private string $key;

    public function __construct(string $key)
    {
        $this->base_url = "https://restapi.amap.com";
        $this->client   = new Client();
        $this->key      = $key;
    }


    /**
     * @param string $city city_name | city_code
     * @param int    $type
     * @return array
     * @throws GuzzleException
     * @throws HttpException
     */
    public function weatherInfo(string $city, int $type = 1)
    {
        $url = sprintf("%s/v3/weather/weatherInfo", $this->base_url);

        if (!\in_array(\strtolower($type), [1, 2])) {
            throw new InvalidArgumentException('Invalid type value(1:base/ 2:all),you are :  '.$type);
        }
        try {
            $response = $this->client->get($url, [
                'key'        => $this->key,
                'city'       => $city,
                'output'     => 'json',
                'extensions' => $type == 1 ? 'base' : 'all',
            ]);
            return json_decode($response->getBody()->getContents(), true) ?: [];
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }


}