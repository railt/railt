<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Serafim\Railgun\Http\Request;

/**
 * Bootstrap section
 */
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../Endpoint.php';

/**
 * Prepare request
 */
$request = Request::create();

/**
 * Show graphiql view when reqest dows not contains schema
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return require __DIR__ . '/../resources/views/graphiql.php';
}

return require __DIR__ . '/../bootstrap.php';
