<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Railgun\Adapters\RequestInterface;
use Railgun\Endpoint;
use Railgun\Http\Request;
use Railgun\Routing\Route;
use Railgun\Routing\Router;

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so we do not have to manually load any of
| our application's PHP classes. It just feels great to relax.
|
*/

require __DIR__ . '/../../vendor/autoload.php';

// App
require __DIR__ . '/src/UsersController.php';


/*
|--------------------------------------------------------------------------
| Create The Endpoint
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Railgun endpoint instance
| which serves as the "glue" for all the components of your application.
|
*/

$endpoint = Endpoint::fromFilePath(__DIR__ . '/schema/index.graphqls');


/*
|--------------------------------------------------------------------------
| Register The GraphQL Auto Loader
|--------------------------------------------------------------------------
|
| Railgun provides a convenient, automatically GraphQL Types loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our GraphQL Types later on. It feels great to relax.
|
*/

$endpoint->autoload()->dir(__DIR__ . '/schema');


/*
|--------------------------------------------------------------------------
| Create The Logger
|--------------------------------------------------------------------------
|
| Let's just use the Monolog library and see what requests are made in
| our greatest application.
|
*/

$logger = new Logger('Railgun', [new StreamHandler('php://stdout')]);

$endpoint->withLogger($logger);

// On all requests
$endpoint->on('request:*', function (RequestInterface $request) use ($endpoint) {
    $endpoint->debug('Request(' . $request->getPath() . ')');
});

// On all responses
$endpoint->on('response:*', function ($response, RequestInterface $request) use ($endpoint) {
    $endpoint->debug('    Body(' . json_encode($response) . ')');
    $endpoint->debug('Response(' . $request->getPath() . ')');
    $endpoint->debug(str_repeat('-', 80));
});

// On all responses
$endpoint->on('route:*', function (Route $route) use ($endpoint) {
    $endpoint->debug('    Route(' . $route->getRoute() . ') => ' . $route->getPattern());
});


/*
|--------------------------------------------------------------------------
| GraphQL Routes
|--------------------------------------------------------------------------
|
| Here is where you can register api routes for your application.
| Now create something great!
|
*/

$endpoint->router(function (Router $router) {
    require __DIR__ . '/routes/routes.php';
    require __DIR__ . '/routes/decorators.php';
});


/*
|--------------------------------------------------------------------------
| Run The Endpoint
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$response = $endpoint->request(Request::create());

$response->send();
