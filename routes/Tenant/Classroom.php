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
    'prefix' => '/api/v1/{tenant}',
    'middleware' => ['tenant.identify', 'tenant.audit']
], function () use ($router) {
    $router->group(['middleware' => 'tenant.auth'], function () use ($router) {
        $router->get('/classrooms', 'ClassroomController@index');
        $router->post('/classrooms', 'ClassroomController@create');
        $router->put('/classrooms/{classroomId}', 'ClassroomController@update');
        $router->delete('/classrooms/{classroomId}', 'ClassroomController@destroy');
    });
});