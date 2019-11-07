<?php

use Monolog\Logger;
use Railt\SDL\Parser;
use Railt\SDL\Compiler;
use Monolog\Handler\StreamHandler;
use Railt\SDL\Linker\LoggerLinker;

require __DIR__ . '/../vendor/autoload.php';

$logger = new Logger('Railt', [
    new StreamHandler('php://stdout', Logger::INFO),
    new StreamHandler(\fopen(__DIR__ . '/logger.out.log', 'wb+')),
]);


$compiler = new Compiler(Compiler::SPEC_JUNE_2018, new Parser($logger));
$compiler->autoload(new LoggerLinker($logger));

$compiler->compile(<<<'GraphQL'
    type Example @deprecated {
        field: Some
    }
GraphQL);
