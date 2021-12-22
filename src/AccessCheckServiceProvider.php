<?php

declare(strict_types=1);
/**
 * This file is part of Tianmiao.
 *
 * @link     https://tianmiao.com
 * @document https://docs.tianmiao.com
 * @contact  tianmiao.com@gmail.com
 * @license  https://tianmiao.com/LICENSE
 */
namespace Tianmiao\AccessCheck;

use Buzz\Client\Curl;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;
use Tianmiao\HttpClient\Browser;

class AccessCheckServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/access_check.php' => 'access_check.php',
        ]);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        app()->singleton('access-check-service', function () {
            return new Browser(['baseUrl' => env('ACCESS_CHECK_BASE_URL', 'http://127.0.0.1:9501')], Curl::class);
        });
    }
}
