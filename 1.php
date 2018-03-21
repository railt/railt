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

$grammar = File::fromPathname(__DIR__ . '/src/SDL/resources/grammar/sdl.pp2');

$schema = File::fromPathname(__DIR__ . '/tests/Compiler/.resources/test.graphqls');

//$parser = new SchemaParser();
$parser = \Railt\Compiler\Parser::fromGrammar($grammar);
$result = $parser->parse($schema);

echo $result;
