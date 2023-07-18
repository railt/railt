<?php

use Railt\SDL\Compiler;
use Railt\SDL\Console\CompileParserCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/vendor/autoload.php';

$command = new CompileParserCommand();
$command->run(new ArgvInput(), new ConsoleOutput());

$compiler = new Compiler();

try {
    $result = $compiler->compile(new \SplFileInfo(__DIR__ . '/test.graphql'));

    dump($result);
} catch (\Throwable $e) {
    \fwrite(\STDERR, "\n\n" . $e . "\n\n");

    //throw $exception;
}
