<?php declare(strict_types=1);
use Railt\GraphQL\Frontend\Parser;
use Railt\Io\File;

require __DIR__ . '/vendor/autoload.php';

$parser = new Parser();

$ast = $parser->parse(File::fromSources('
type A {}
'));

dd($ast);
