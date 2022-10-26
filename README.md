# 高德天气sdk
> 之前项目用过,但写的比较随意,刚好看到比较优质的教程,学习一下

## Installing

```shell
$ composer require madou1217/weather -vvv
``` 

### 使用方法:

```php 
<?php
require __DIR__ .'/vendor/autoload.php';

use Madou1217\Weather\Weather;

// 高德开放平台应用 Key 
$key = 'xxxxxxxxxxxxxxxxxxxxx';
$weather = new Weather($key);

// 城市 , 城市名称 或者 高德地图中城市对应的 adcode
$city_name = "乌鲁木齐市"

var_dump($weather->weatherInfo($city_name));

// 获取实时天气 
$city_code = "654000";
$weather->getLiveWeather($city_code);

// 获取预报天气
$weather->getForecastWeather($city_code);

```

### 在Laravel中使用:

.env 中添加:

```
AMAP_WEATHER_KEY=xxxxxxxxx
```

config/weathers.php 中：

``` php
<?php 

return [
    'amap' => [
            'key' => env('AMAP_WEATHER_KEY','U_DEFAULT_KEY'),
    ],
]
```

#### 可以用两种方式来获取 Madou1217\Weather\Weather 实例：

- 方法参数注入

```php
<?php 

use Madou1217\Weather\Weather;

public function weather(Weather $weather) 
{
        $response = $weather->weatherInfo('乌鲁木齐市');
        //TODO ...
}
```

- 服务名访问

```php
<?php 

use Madou1217\Weather\Weather;

public function weather() 
{
    $response = app('weather')->weatherInfo('乌鲁木齐市');
    // TODO ...
}
```


##  参考
- ### 学习地址: https://learnku.com/courses/creating-package
- ### [高德开放平台](https://lbs.amap.com/api/webservice/guide/api/weatherinfo)


## License

MIT