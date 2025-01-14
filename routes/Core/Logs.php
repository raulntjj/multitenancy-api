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
    $router->group(['middleware' => 'core.auth'], function () use ($router) {
        $router->get('logs', 'AuditLogController@index');
    });
});