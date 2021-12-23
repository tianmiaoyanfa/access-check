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

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tianmiao\Http\BadRequestException;
use Tianmiao\Http\ForbiddenException;
use Tianmiao\Http\UnauthorizedException;
use Tianmiao\HttpClient\Browser;

class LaravelMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $service = app('access-check-service');
        /* @var Browser $service */
        $data = $this->collector($request);
        $response = $service->post('/access/check', json_encode($data), [
            'Authorization' => 'Bearer ' . $this->getToken($request),
            'Route' => $this->getRoute($request),
            'Project' => env('ACCESS_CHECK_PROJECT', 'dev'),
            'Content-Type' => 'application/json',
        ]);
        if($response->getStatusCode() >= 200 && $response->getStatusCode() < 400) {
            return $next($request);
        } elseif ($response->getStatusCode() === 401) {
            throw new UnauthorizedException($response->toArray()['message']);
        } elseif ($response->getStatusCode() === 403) {
            throw new ForbiddenException($response->toArray()['message']);
        } else {
            throw new BadRequestException($response->toArray()['message']);
        }
    }

    public function collector(Request $request)
    {
        $data['ip'] = $request->getClientIp();
        $data['user_agent'] = $request->header('User-Agent');
        $data['path'] = $request->getPathInfo();
        $data['host'] = $request->getHost();
        $data['method'] = strtolower($request->getMethod());
        $data['query'] = $request->query();
        $data['url'] = $request->getUri();
        $data['post'] = $request->post();
        $data['server'] = $_SERVER;

        return $data;
    }

    public function getRoute(Request $request)
    {
        $routes = Route::getRoutes();
        $route = $request->getPathInfo() . ':' . strtolower($request->getMethod());
        return $route;
    }

    public function getToken(Request $request)
    {
        $token = $request->bearerToken() ?: $request->header('Token');
        return $token;
    }

}
