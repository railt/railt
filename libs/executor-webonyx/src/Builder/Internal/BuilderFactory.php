<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder\Internal;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Railt\Executor\Webonyx\Builder\Builder;
use Railt\Executor\Webonyx\Builder\DirectiveBuilder;
use Railt\Executor\Webonyx\Builder\EnumTypeBuilder;
use Railt\Executor\Webonyx\Builder\InputObjectTypeBuilder;
use Railt\Executor\Webonyx\Builder\InterfaceTypeBuilder;
use Railt\Executor\Webonyx\Builder\ObjectTypeBuilder;
use Railt\Executor\Webonyx\Builder\ScalarTypeBuilder;
use Railt\Executor\Webonyx\Builder\UnionTypeBuilder;
use Railt\TypeSystem\Definition\DirectiveDefinition;
use Railt\TypeSystem\Definition\NamedTypeDefinitionInterface;
use Railt\TypeSystem\Definition\Type\EnumType;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\Definition\Type\InterfaceType;
use Railt\TypeSystem\Definition\Type\ObjectType;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\Definition\Type\UnionType;
use Railt\TypeSystem\DictionaryInterface;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\Executor\Webonyx
 */
final class BuilderFactory
{
    /**
     * @var array<class-string<NamedTypeDefinitionInterface>, class-string<Builder>>
     */
    private const BUILDER_MAPPINGS = [
        ObjectType::class => ObjectTypeBuilder::class,
        InterfaceType::class => InterfaceTypeBuilder::class,
        ScalarType::class => ScalarTypeBuilder::class,
        InputObjectType::class => InputObjectTypeBuilder::class,
        UnionType::class => UnionTypeBuilder::class,
        EnumType::class => EnumTypeBuilder::class,
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
