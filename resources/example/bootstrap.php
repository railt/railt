<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railgun\Example\Endpoint;

/**
 * Configs
 */
$root        = __DIR__ . '/graphql';
$schema      = $root . '/schema.graphqls';

$directories = [
    $root . '/models',
    $root . '/scalars',
    $root . '/support',
];


/**
 * Resolve schema
 */
$endpoint = $request->has('schema') &&
    trim(preg_replace('/\s*^#.*?$/ismu', '', $request->get('schema')))
        ? Endpoint::sources($request->get('schema'))
        : Endpoint::filePath($schema);

$endpoint->autoloadDirectory(...$directories);

/**
 * Router
 */
$router = $endpoint->routes();
$router->when('{any}/id')->where('any', '.*?');

/**
 * Process HTTP Request
 */
$response = $endpoint->request($request);

/**
 * Process HTTP Response
 */
header('Content-Type: application/json');
echo json_encode($response);
