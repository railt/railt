<?php

use Railt\Io\File;
use Railt\SDL\Compiler\SymbolTable\Builder;
use Railt\SDL\Parser\Factory;

require __DIR__ . '/vendor/autoload.php';



/* ============================= */
$src    = File::fromPathname(__DIR__ . '/1.graphqls');
$parser = (new Factory())->getParser();
$ast    = $parser->parse($src->getContents());
/* ============================= */

$pipe = new \Railt\SDL\Compiler\Pipeline();
$result = $pipe->process($ast);

dd($result);
