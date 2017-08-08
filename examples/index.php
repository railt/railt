<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Serafim\Railgun\Http\Request;
use Serafim\Railgun\Runtime\Endpoint;


require __DIR__ . '/../vendor/autoload.php';

// Endpoint
$endpoint = Endpoint::fromFilePath(__DIR__ . '/gql/schema.graphqls')
    ->debugMode()
    ->autoloadDirectory(__DIR__ . '/gql');

// Request
$response = $endpoint->request(Request::create());

// Response
header('Content-Type: application/json');
echo json_encode($response);

