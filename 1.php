<?php declare(strict_types=1);

use Railt\Compiler\Debug\NodeDumper;
use Railt\Io\File;
use Railt\SDL\Compiler\Parser\Factory;
use Railt\SDL\Compiler\Pipeline;

require __DIR__ . '/vendor/autoload.php';

$sources = File::fromPathname(__DIR__ . '/gql/schema.graphqls');

$pipeline = new Pipeline();
$r = $pipeline->process($sources);

dd($r);
