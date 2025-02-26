<?php

use Illuminate\Http\Request;
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
    'prefix' => '/api/v1',
    'middleware' => ['tenant.identify', 'tenant.audit']
], function () use ($router) {
    $router->get('/', function (Request $request) {
        $tenantSlug = $request->route('tenant');
        return UtilityHelper::ping($tenantSlug);
    });

    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');

    $router->group(['middleware' => 'tenant.auth'], function () use ($router) {
        $router->get('/me', 'ProfileController@show');
        $router->put('/me', 'ProfileController@update');
        $router->delete('/me', 'ProfileController@destroy');

        $router->get('logs', 'AuditLogController@index');

        $router->get('/users', 'UserController@index');
        $router->get('/users/{user}', 'UserController@show');
        $router->post('/users', 'UserController@create');
        $router->put('/users/{user}', 'UserController@update');
        $router->delete('/users/{user}', 'UserController@destroy');
    });
});