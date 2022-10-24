<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Madou1217\Weather\Exceptions\HttpException;
use Madou1217\Weather\Exceptions\InvalidArgumentException;
use Madou1217\Weather\Weather;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    // 检查 $type 参数
    /**
     * @throws GuzzleException
     * @throws HttpException
     */
    public function testGetWeatherWithInvalidType()
    {
        $w = new Weather('mock-key');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type value(base / all),you are: foo');
        $w->weatherInfo('乌鲁木齐市', 'foo');
        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }


    /**
     * @throws GuzzleException
     * @throws InvalidArgumentException
     * @throws HttpException
     */
    public function testGetWeatherInfo()
    {
        // 创建模拟接口响应值。
        $response = new Response(200, [], '{"success": true}');

        // 创建模拟 http client。
        $client = \Mockery::mock(Client::class);

        // 指定将会产生的行为（在后续的测试中将会按下面的参数来调用）。
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key'        => 'mock-key',
                'city'       => '乌鲁木齐市',
                'output'     => 'json',
                'extensions' => 'base',
            ],
        ])->andReturn($response);

        // 将 `getHttpClient` 方法替换为上面创建的 http client 为返回值的模拟方法。
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client); // $client 为上面创建的模拟实例。

        // 然后调用 `getWeather` 方法，并断言返回值为模拟的返回值。
        $this->assertSame(['success' => true], $w->weatherInfo('乌鲁木齐市'));
    }


    public function testGetLiveWeather()
    {
        // 将 getWeather 接口模拟为返回固定内容，以测试参数传递是否正确
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->expects()->weatherInfo('乌鲁木齐市', 'base')->andReturn(['success' => true]);

        // 断言正确传参并返回
        $this->assertSame(['success' => true], $w->getLiveWeather('乌鲁木齐市'));
    }

    public function testGetForecastsWeather()
    {
        // 将 getWeather 接口模拟为返回固定内容，以测试参数传递是否正确
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->expects()->weatherInfo('乌鲁木齐市', 'all')->andReturn(['success' => true]);

        // 断言正确传参并返回
        $this->assertSame(['success' => true], $w->getForecastWeather('乌鲁木齐市'));
    }
}