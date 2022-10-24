<?php

namespace Madou1217\Weather;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Madou1217\Weather\Exceptions\HttpException;
use Madou1217\Weather\Exceptions\InvalidArgumentException;

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
     * @param string $type 可选值：base/all
     *
     * base:返回实况天气
     * all:返回预报天气
     *
     * @return array
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function weatherInfo(string $city, string $type = 'base'): array

    {
        $url = sprintf("%s/v3/weather/weatherInfo", $this->base_url);
        if (!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base / all),you are: '.$type);
        }

        $query = [
            'key'        => $this->key,
            'city'       => $city,
            'output'     => 'json',
            'extensions' => $type,
        ];
        try {
            $response = $this->client->get($url, [
                'query' => $query,
            ]);
            return json_decode($response->getBody()->getContents(), true) ?: [];
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 获取实时天气
     */
    public function getLiveWeather(string $city): array
    {
        return $this->weatherInfo($city, 'base');
    }

    /**
     * 返回预报天气
     */
    public function getForecastWeather(string $city): array
    {
        return $this->weatherInfo($city, 'all');
    }
}