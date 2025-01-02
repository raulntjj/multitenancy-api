<?php

use App\Helpers\UtilityHelper;

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

$router->group([
    'prefix' => '/api/v1/core',
    'middleware' => 'core.audit'
], function () use ($router) {
    $router->get('/', function () {
        return UtilityHelper::ping('MultiTenancy');
    });

    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');

    $router->group(['middleware' => 'core.auth'], function () use ($router) {
        $router->get('me', 'ProfileController@show');
        $router->put('me', 'ProfileController@update');
        $router->delete('me', 'ProfileController@destroy');

        $router->get('tenants', 'TenantController@index');
        $router->post('tenants', 'TenantController@store');
        $router->get('tenants/{slug}', 'TenantController@show');
        $router->put('tenants/{slug}', 'TenantController@update');
        $router->delete('tenants/{slug}', 'TenantController@destroy');
    });
});