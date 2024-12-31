<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'core'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');

    $router->group(['middleware' => 'core.auth'], function () use ($router) {
        $router->get('me', 'UserController@show');
        $router->put('me', 'UserController@update');
        $router->delete('me', 'UserController@delete');

        $router->get('tenants', 'AuthController@show');
        $router->get('tenants/{slug}', 'AuthController@show');
        $router->post('tenants', 'AuthController@store');
        $router->put('tenants/{slug}', 'AuthController@update');
        $router->delete('tenants/{slug}', 'AuthController@destroy');
    });
});