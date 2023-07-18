<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx\Internal;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Railt\SDL\DictionaryInterface;
use Railt\TypeSystem\Bridge\Webonyx\Builder;
use Railt\TypeSystem\Bridge\Webonyx\BuilderInterface;
use Railt\TypeSystem\Bridge\Webonyx\DirectiveBuilder;
use Railt\TypeSystem\Bridge\Webonyx\EnumTypeBuilder;
use Railt\TypeSystem\Bridge\Webonyx\InputObjectTypeBuilder;
use Railt\TypeSystem\Bridge\Webonyx\InterfaceTypeBuilder;
use Railt\TypeSystem\Bridge\Webonyx\ObjectTypeBuilder;
use Railt\TypeSystem\Bridge\Webonyx\ScalarTypeBuilder;
use Railt\TypeSystem\Bridge\Webonyx\UnionTypeBuilder;
use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\EnumTypeDefinition;
use Railt\TypeSystem\InputObjectTypeDefinition;
use Railt\TypeSystem\InterfaceTypeDefinition;
use Railt\TypeSystem\NamedTypeDefinitionInterface;
use Railt\TypeSystem\ObjectTypeDefinition;
use Railt\TypeSystem\ScalarTypeDefinition;
use Railt\TypeSystem\UnionTypeDefinition;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\TypeSystem\Bridge\Webonyx
 */
final class BuilderFactory
{
    /**
     * @var array<class-string<NamedTypeDefinitionInterface>, class-string<Builder>>
     */
    private const BUILDER_MAPPINGS = [
        ObjectTypeDefinition::class => ObjectTypeBuilder::class,
        InterfaceTypeDefinition::class => InterfaceTypeBuilder::class,
        ScalarTypeDefinition::class => ScalarTypeBuilder::class,
        InputObjectTypeDefinition::class => InputObjectTypeBuilder::class,
        UnionTypeDefinition::class => UnionTypeBuilder::class,
        EnumTypeDefinition::class => EnumTypeBuilder::class,
    ];

    private readonly Registry $types;

    private readonly DirectiveBuilder $directives;

    /**
     * @var array<class-string<NamedTypeDefinitionInterface>, Builder>
     */
    private array $builders = [];

    public function __construct()
    {
        $this->types = new Registry();
        $this->directives = new DirectiveBuilder($this);
    }

    /**
     * @psalm-suppress all : psalm false-positive (bug)
     */
    private function getBuilder(NamedTypeDefinitionInterface $type): Builder
    {
        if (isset($this->builders[$type::class])) {
            return $this->builders[$type::class];
        }

        $class = self::BUILDER_MAPPINGS[$type::class]
            ?? throw new \OutOfRangeException(
                \sprintf('Could not find builder for type "%s"', $type::class)
            );

        return $this->builders[$type::class] = new $class($this);
    }

    private function buildType(NamedTypeDefinitionInterface $type): Type
    {
        $builder = $this->getBuilder($type);

        /** @var Type */
        return $builder->build($type);
    }

    private function buildDirective(DirectiveDefinition $directive): Directive
    {
        return $this->directives->build($directive);
    }

    public function getDirective(DirectiveDefinition $directive): Directive
    {
        if ($this->types->hasDirective($directive->getName())) {
            return $this->types->getDirective($directive->getName());
        }

        $this->types->setDirective($result = $this->buildDirective($directive));

        return $result;
    }

    public function getType(NamedTypeDefinitionInterface $type): Type
    {
        if ($this->types->hasType($type->getName())) {
            return $this->types->getType($type->getName());
        }

        $this->types->setType($result = $this->buildType($type));

        return $result;
    }

    public function getSchema(DictionaryInterface $types): Schema
    {
        $schema = $types->findSchemaDefinition();

        if ($schema === null) {
            throw new \OutOfRangeException('Schema not defined');
        }

        $result = [
            'types' => [],
            'directives' => [],
        ];

        if ($mutation = $schema->getQueryType()) {
            $result['query'] = $this->getType($mutation);
        }

        if ($mutation = $schema->getMutationType()) {
            $result['mutation'] = $this->getType($mutation);
        }

        if ($subscription = $schema->getSubscriptionType()) {
            $result['subscription'] = $this->getType($subscription);
        }

        foreach ($types->getTypeDefinitions() as $type) {
            $result['types'][] = $this->getType($type);
        }

        foreach ($types->getDirectiveDefinitions() as $directive) {
            $result['directives'][] = $this->getDirective($directive);
        }

        return new Schema($result);
    }
}
