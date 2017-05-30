<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Http\Request;
use Serafim\Railgun\Example\Queries\UsersQuery;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| So let us turn on the lights! For a start, we need to create our point of
| interaction (aka Endpoint). Let's create it and add there references to
| requests, mutations and other "magic" things.
|
*/

$endpoint = new Endpoint('test');


$endpoint->addQuery(UsersQuery::class);

$response = $endpoint->request(Request::create());

header('Content-Type: application/json');

echo json_encode($response);

