<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Io\File;
use Railt\SDL\Parser\SchemaParser;

require __DIR__ . '/vendor/autoload.php';

$schema = File::fromPathname(__DIR__ . '/tests/Compiler/.resources/test.graphqls');

$parser = new SchemaParser();
$result = $parser->parse($schema);

echo $result;



