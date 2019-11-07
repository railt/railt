<?php declare(strict_types=1);

use Railt\SDL\Compiler;

require __DIR__ . '/../vendor/autoload.php';

//
// Initialize empty compiler
//
$compiler = new Compiler(Compiler::SPEC_RAILT);

//
// Execute some code
//
$document = $compiler->compile(<<<'GraphQL'
    schema {
        query: A
        mutation: B
    }
    
    """
    This is a description
    of the `Foo` type.
    """
    type Foo implements Bar & Baz {
        "Description of the `one` field."
        one: A
        """
        This is a description of the `two` field.
        """
        two(
            """
            This is a description of the `argument` argument.
            """
            argument: A!
        ): A
        """This is a description of the `three` field."""
        three(argument: A, other: String): Int
        four(argument: String = "string"): String
        five(argument: [String] = ["string", "string"]): String
        six(argument: Type = {key: "value"}): A
        seven(argument: Int = null): A
    }
    
    type AnnotatedObject @onObject(arg: "value") {
        annotatedField(arg: A = "default" @onArgumentDefinition): Type @onField
    }
    
    type UndefinedType
    
    extend type Foo {
        seven(argument: [String]): A
    }
    
    extend type Foo @onType
    
    interface Bar {
        one: Type
        four(argument: String = "string"): String
    }
    
    interface AnnotatedInterface @onInterface {
        annotatedField(arg: Type @onArgumentDefinition): Type @onField
    }
    
    interface UndefinedInterface
    
    extend interface Bar {
        two(argument: Type!): Type
    }
    
    extend interface Bar @onInterface

    ##
    type A
    type B
    type Type
    interface Baz
    directive @onField on FIELD
    directive @onArgumentDefinition on ARGUMENT_DEFINITION
    directive @onObject on OBJECT
    directive @onType on OBJECT
    directive @onInterface on INTERFACE
GraphQL
);

dump($document);
