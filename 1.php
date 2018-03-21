<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Compiler\Grammar\Reader;
use Railt\Io\File;
use Railt\SDL\Parser\SchemaParser;

require __DIR__ . '/vendor/autoload.php';

$schema = File::fromPathname(__DIR__ . '/tests/Compiler/.resources/test.graphqls');

$time = \microtime(true);

$parser = new SchemaParser();
$result = $parser->parse($schema);

echo \number_format(\microtime(true) - $time, 5) . 'ms' . "\n";
echo \number_format(\memory_get_usage() / 1000 / 1000, 2) . 'mb';
