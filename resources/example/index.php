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
    $endpoint->debugMessage('Request(' . $request->getPath() . ')');
});

// On all responses
$endpoint->on('response:*', function ($response, RequestInterface $request) use ($endpoint) {
    $endpoint->debugMessage('    Body(' . json_encode($response) . ')');
    $endpoint->debugMessage('Response(' . $request->getPath() . ')');
    $endpoint->debugMessage(str_repeat('-', 80));
});

// On all responses
$endpoint->on('route:*', function (Route $route) use ($endpoint) {
    $endpoint->debugMessage('    Route(' . $route->getRoute() . ') => ' . $route->getPattern());
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

function fake_user($id): array {
    return [
        'id'        => $id,
        'login'     => 'Fake User #' . $id,
        'createdAt' => new DateTime(),
        'updatedAt' => new DateTime(),
    ];
}

$endpoint->router(function (Router $router) {

    $user = $router->when('user', function () {
        return fake_user(random_int(1, 100));
    });


    $router->when('friends', function (RequestInterface $request) {
        $count = $request->get('count', 10);
        $count = max(1, min($count, 100));

        $iterator = range(1, $count);

        foreach ($iterator as $i) {
            yield fake_user($i);
        }
    })->inside($user);


    // All dates
    $router->when('{any}.{dateTime}', function (RequestInterface $request, DateTimeInterface $time) {
        $key    = 'DateTime::' . $request->get('format');
        $format = defined($key) ? constant($key) : DateTime::RFC3339;

        return $time->format($format);
    })
        ->where('any', '.*?')
        ->where('dateTime', 'createdAt|updatedAt');
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
