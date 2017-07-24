<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Serafim\Railgun\Compiler\Compiler;

require __DIR__ . '/../vendor/autoload.php';

$compiler = new Compiler();
$ast = $compiler->parseFile(__DIR__ . '/schema.graphqls');

dd($ast->dump());

