<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Serafim\Railgun\Idl\Compiler;

require __DIR__ . '/../vendor/autoload.php';

try {
    $schema = new Compiler();
    $result = $schema->compile(__DIR__ . '/schema.graphqls');

    dd($result);
} catch (Throwable $e) {
    echo $e->getMessage();
    throw $e;
}

