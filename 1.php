<?php declare(strict_types=1);

use Railt\Compiler\Debug\LexerDumper;
use Railt\Compiler\Debug\NodeDumper;
use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Lexer;
use Railt\Compiler\Parser;
use Railt\Io\File;
use Railt\SDL\Compiler\Parser\Factory;
use Railt\SDL\Compiler\SymbolTable\Builder;

require __DIR__ . '/vendor/autoload.php';

$sources = File::fromPathname(__DIR__ . '/gql/schema.graphqls');

$dumper = new NodeDumper((new Factory())->getParser()->parse($sources->getContents()));
dd($dumper->toXml());

dd((new Builder())->build($sources));

