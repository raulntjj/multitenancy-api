<?php

require_once __DIR__.'/../vendor/autoload.php';
use App\Helpers\UtilityHelper;

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();

// $app->withFactories('/../database/factories');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

use Illuminate\Contracts\Routing\ResponseFactory;
use Laravel\Lumen\Http\ResponseFactory as LumenResponseFactory;

$app->singleton(ResponseFactory::class, function ($app) {
    return new LumenResponseFactory($app, $app['router']);
});

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/
$app->router->get('/', function () {
    return UtilityHelper::ping();
});

$app->router->group([
    'namespace' => 'App\Core\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/core.php';
});

$app->router->group([
    'namespace' => 'App\Tenant\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/tenant.php';
});

// Fallback
$app->router->get('/{any:.*}', function () {
    throw new App\Exceptions\HandleException('Route not found', 404);
});



$app->routeMiddleware([
    'core.auth' => App\Core\Http\Middleware\CoreAuthMiddleware::class,
    'core.audit' => App\Core\Http\Middleware\CoreAuditMiddleware::class,
    'tenant.auth' => App\Tenant\Http\Middleware\TenantAuthMiddleware::class,
    'tenant.audit' => App\Tenant\Http\Middleware\TenantAuditMiddleware::class,
    'tenant.identify' => App\Tenant\Http\Middleware\IdentifyTenant::class,
]);

return $app;
