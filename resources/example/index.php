<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railgun\Endpoint;
use Railgun\Http\Request;

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

$endpoint = Endpoint::new(__DIR__ . '/schema.graphqls');

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

echo json_encode($response);

