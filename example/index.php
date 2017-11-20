<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Railt\Compiler\Compiler;
use Railt\Compiler\Persisting\Psr16Persister;
use Railt\Endpoint;
use Railt\Http\Request;
use Railt\Reflection\Filesystem\File;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Example exception handler.
 */
$whoops = new Whoops();

$isHttpRequest = \function_exists('getallheaders');
$isJsonRequest = $isHttpRequest && (((array)\getallheaders())['Content-Type'] ?? '') === 'application/json';

$whoops->pushHandler($isJsonRequest ? new JsonResponseHandler() : new PrettyPageHandler());
$whoops->register();

/**
 * Create an abstract file system for saving and reading cache files.
 */
$fs = new Filesystem(new Local(__DIR__ . '/storage/'));

/**
 * Create the cache persister driver.
 */
$cache = new Psr16Persister(new FilesystemCachePool($fs, '/'));

/**
 * Create the GraphQL compiler.
 */
$compiler = new Compiler($cache);

$compiler->autoload(function (string $type): ?string {
    $path = \sprintf('%s/gql/%s.graphqls', __DIR__, $type);

    return \is_file($path) ? $path : null;
});

/**
 * Read GraphQL index file.
 */
$schema = File::fromPathname(__DIR__ . '/schema.graphqls');

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$request  = new Request();
$endpoint = new Endpoint($compiler, $schema);

$response = $endpoint->request($request);

$response->send();
