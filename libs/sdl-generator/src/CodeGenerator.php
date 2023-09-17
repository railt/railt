<?php

declare(strict_types=1);

namespace Railt\SDL\Generator;

use Railt\SDL\Generator\Definition\DirectiveDefinitionGenerator;
use Railt\SDL\Generator\Definition\SchemaDefinitionGenerator;
use Railt\SDL\Generator\Type\EnumTypeDefinitionGenerator;
use Railt\SDL\Generator\Type\InputObjectTypeDefinitionGenerator;
use Railt\SDL\Generator\Type\InterfaceTypeDefinitionGenerator;
use Railt\SDL\Generator\Type\ObjectTypeDefinitionGenerator;
use Railt\SDL\Generator\Type\ScalarTypeDefinitionGenerator;
use Railt\SDL\Generator\Type\UnionTypeDefinitionGenerator;
use Railt\TypeSystem\Definition\Type\EnumType;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\Definition\Type\InterfaceType;
use Railt\TypeSystem\Definition\Type\ObjectType;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\Definition\Type\UnionType;
use Railt\TypeSystem\DictionaryInterface;

final class CodeGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly DictionaryInterface $types,
        private readonly Config $config = new Config()
    ) {}

    /**
     * @return iterable<GeneratorInterface>
     */
    private function generators(): iterable
    {
        if ($schema = $this->types->findSchemaDefinition()) {
            yield new SchemaDefinitionGenerator($schema, $this->config);
        }

        foreach ($this->types->getDirectiveDefinitions() as $directive) {
            yield new DirectiveDefinitionGenerator($directive, $this->config);
        }

        foreach ($this->types->getTypeDefinitions() as $type) {
            yield match (true) {
                $type instanceof EnumType => new EnumTypeDefinitionGenerator($type, $this->config),
                $type instanceof InputObjectType => new InputObjectTypeDefinitionGenerator($type, $this->config),
                $type instanceof InterfaceType => new InterfaceTypeDefinitionGenerator($type, $this->config),
                $type instanceof ObjectType => new ObjectTypeDefinitionGenerator($type, $this->config),
                $type instanceof ScalarType => new ScalarTypeDefinitionGenerator($type, $this->config),
                $type instanceof UnionType => new UnionTypeDefinitionGenerator($type, $this->config),
                default => throw new \InvalidArgumentException(
                    \sprintf('Could not print %s type', \get_debug_type($type))
                ),
            };
        }
    }

    public function __toString(): string
    {
        $result = '';

        foreach ($this->generators() as $generator) {
            $result .= (string)$generator
                . $this->config->delimiter
                . $this->config->delimiter
            ;
        }

        return $result;
    }
}
