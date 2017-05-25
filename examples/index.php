<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Illuminate\Http\Request;
use Serafim\Railgun\Endpoint;
use Illuminate\Http\JsonResponse;
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

//
// Add a simple query named "users"
//

$endpoint->query('users', new UsersQuery());


try {

    /*
    |--------------------------------------------------------------------------
    | Run The Application
    |--------------------------------------------------------------------------
    |
    | Once we have the application, we can handle the incoming request
    | through and send the associated response back to
    | the client's browser allowing them to enjoy the creative
    | and wonderful application we have prepared for them.
    |
    */

    $request    = Request::createFromGlobals();
    $gql        = Request::create($request);

    $response   = $endpoint->request($gql);

} catch (Throwable $e) {

    /*
    |--------------------------------------------------------------------------
    | Catch 'em All!
    |--------------------------------------------------------------------------
    |
    | Don't worry about runtime exceptions. We'll try to catch and format them.
    |
    */

    $response = Endpoint::error($e);

} finally {

    /*
    |--------------------------------------------------------------------------
    | Send The Response
    |--------------------------------------------------------------------------
    |
    | Finally send our very important answer in json format and
    | close the connection.
    |
    */

    (new JsonResponse($response))->send();
}





