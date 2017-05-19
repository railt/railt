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
use Serafim\Railgun\Requests\Factory;
use Serafim\Railgun\Tests\Queries\ArticlesQuery;

require __DIR__ . '/../vendor/autoload.php';


$endpoint = (new Endpoint('test'))
    ->query('articles', new ArticlesQuery());


try {
    $response = $endpoint->request(Factory::create(Request::createFromGlobals()));
} catch (Throwable $e) {
    $response = [
        'data'   => [],
        'errors' => [
            [
                'message'   => $e->getMessage() . "\n" . $e->getTraceAsString(),
                'locations' => [
                    'line'   => $e->getLine(),
                    'column' => 0,
                ]
            ],
        ],
    ];
} finally {
    (new JsonResponse($response))->send();
}





