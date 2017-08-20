<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

/** @var \Railgun\Routing\Router $router */

$router->when('{any}.{dateTime}', 'Example\\SupportController@dateTime')
    ->where('any', '.*?')
    ->where('dateTime', 'createdAt|updatedAt');
