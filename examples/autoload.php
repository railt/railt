<?php declare(strict_types=1);

use Railt\SDL\Compiler;
use Railt\SDL\Linker\Linker;
use Railt\SDL\TypeSystem\Directive;
use Railt\SDL\TypeSystem\Schema;
use Railt\SDL\TypeSystem\Type\ObjectType;

require __DIR__ . '/../vendor/autoload.php';

//
// Initialize empty compiler
//
$compiler = new Compiler(Compiler::SPEC_RAW);

//
// When the compiler requires loading of some type
// then we create it dynamically.
//
$compiler->autoload(static function (int $type, ?string $name) use ($compiler): void {
    switch (true) {
        case Linker::wantsType($type):
            $compiler->withType(new ObjectType(['name' => $name]));
            break;

        case Linker::wantsDirective($type):
            $compiler->withDirective(new Directive(['name' => $name]));
            break;

        case Linker::wantsSchema($type):
            $compiler->withSchema(new Schema());
            break;
    }
});

//
// Execute some code
//
$document = $compiler->compile(<<<'GraphQL'
    type Query {
        field: Some
    }

    extend schema @test
GraphQL
);

dump($document);
