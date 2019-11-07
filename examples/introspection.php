<?php declare(strict_types=1);

use Railt\SDL\Compiler;

require __DIR__ . '/../vendor/autoload.php';

dump(
    (new Compiler(Compiler::SPEC_INTROSPECTION))
        ->getDocument()
);
