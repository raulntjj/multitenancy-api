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
    $router->post('login', 'Core\Http\Controllers\AuthController@login');

    $router->group(['middleware' => 'core.auth'], function () use ($router) {
        $router->post('tenants', 'Core\Http\Controllers\TenantController@store');
    });
});


$router->group([
    'prefix' => '{tenant}/api/v1',
    'middleware' => 'identify.tenant'
], function () use ($router) {
    $router->post('login', 'Tenant\Http\Controllers\AuthController@login');

    $router->group(['middleware' => 'tenant.auth'], function () use ($router) {
    });
});


