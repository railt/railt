<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;
use Railt\Introspection\Exception\IntrospectionException;
use Railt\Introspection\Parser;
use Railt\TypeSystem\Argument;
use Railt\TypeSystem\Directive;
use Railt\TypeSystem\EnumValue;
use Railt\TypeSystem\Field;
use Railt\TypeSystem\InputField;
use Railt\TypeSystem\Reference\TypeReference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\ListType;
use Railt\TypeSystem\Type\NonNullType;

/**
 * Class Registry
 */
class Registry
{
    /**
     * @var string
     */
    private const ERROR_BAD_BUILDER = 'Can not resolve builder for GraphQL type %s';

    /**
     * @var string
     */
    private const ERROR_BAD_TYPE = 'Can not find GraphQL type %s';

    /**
     * @var string[]|BuilderInterface[]
     */
    private const DEFAULT_BUILDERS = [
        ObjectTypeBuilder::class,
        ScalarTypeBuilder::class,
        EnumTypeBuilder::class,
        InterfaceTypeBuilder::class,
        InputObjectTypeBuilder::class,
        UnionTypeBuilder::class,
    ];

    /**
     * @var array|BuilderInterface[]
     */
    private array $preloaded = [];

    /**
     * @var Parser
     */
    private Parser $parser;

    /**
     * @var Schema
     */
    private Schema $schema;

    /**
     * Registry constructor.
     *
     * @param Schema $schema
     * @param array $types
     * @throws IntrospectionException
     */
    public function __construct(Schema $schema, array $types)
    {
        $this->schema = $schema;
        $this->parser = new Parser();

        $this->preloadTypes($types);
    }

    /**
     * @param array $types
     * @return void
     * @throws IntrospectionException
     */
    private function preloadTypes(array $types): void
    {
        foreach ($types as $type) {
            ['kind' => $kind, 'name' => $name] = $type;

            foreach (self::DEFAULT_BUILDERS as $class) {
                if ($class::match($kind)) {
                    $this->preloaded[$name] = new $class($this, $type);

                    continue 2;
                }
            }

            /** @noinspection DisconnectedForeachInstructionInspection */
            throw new IntrospectionException(
                \sprintf(self::ERROR_BAD_BUILDER, $kind)
            );
        }
    }

    /**
     * @param array $field
     * @return FieldInterface
     * @throws \Throwable
     */
    public function field(array $field): FieldInterface
    {
        $arguments = \array_map(fn(array $data) => $this->argument($data), $field['args'] ?? []);

        return new Field($field['name'], $this->type($field['type']), [
            'description'       => $field['description'] ?? null,
            'arguments'         => $arguments,
            'deprecationReason' => $field['deprecationReason'] ?? null,
        ]);
    }

    /**
     * @param array $argument
     * @return ArgumentInterface
     * @throws \Throwable
     */
    public function argument(array $argument): ArgumentInterface
    {
        return new Argument($argument['name'], $this->type($argument['type']), [
            'description'  => $argument['description'] ?? null,
            'defaultValue' => $this->parseDefaultValue($argument['defaultValue']),
        ]);
    }

    /**
     * @param array $type
     * @return TypeInterface|TypeReferenceInterface
     * @throws \Throwable
     */
    public function type(array $type)
    {
        switch ($type['kind']) {
            case 'NON_NULL':
                return new NonNullType($this->type($type['ofType']));

            case 'LIST':
                return new ListType($this->type($type['ofType']));

            default:
                return $this->reference($type['name']);
        }
    }

    /**
     * @param string $name
     * @return TypeReferenceInterface
     */
    public function reference(string $name): TypeReferenceInterface
    {
        return new TypeReference($this->schema, $name);
    }

    /**
     * @param mixed $value
     * @return bool|int|mixed|string|array|null
     * @throws \Throwable
     */
    protected function parseDefaultValue($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            $result = $this->parser->parse($value);

            return $result[0][0];
        } catch (ParserRuntimeExceptionInterface $e) {
            throw new IntrospectionException('Can not parse default value ' . $value);
        }
    }

    /**
     * @param string $type
     * @return NamedTypeInterface|TypeInterface
     * @throws IntrospectionException
     */
    public function get(string $type): NamedTypeInterface
    {
        if (! isset($this->preloaded[$type])) {
            throw new IntrospectionException(
                \sprintf(self::ERROR_BAD_TYPE, $type)
            );
        }

        return $this->preloaded[$type]->getType();
    }

    /**
     * @param array $directive
     * @return DirectiveInterface
     * @throws \Throwable
     */
    public function directive(array $directive): DirectiveInterface
    {
        $arguments = \array_map(fn(array $data) => $this->argument($data),
            $field['args'] ?? []
        );

        return new Directive($directive['name'], [
            'description' => $directive['description'] ?? null,
            'locations'   => $directive['locations'] ?? [],
            'arguments'   => $arguments,
        ]);
    }

    /**
     * @param array $field
     * @return InputFieldInterface
     * @throws \Throwable
     */
    public function inputField(array $field): InputFieldInterface
    {
        return new InputField($field['name'], $this->type($field['type']), [
            'description'  => $field['description'] ?? null,
            'defaultValue' => $this->parseDefaultValue($field['defaultValue']),
        ]);
    }

    /**
     * @param array $value
     * @return EnumValueInterface
     * @throws \Throwable
     */
    public function enumValue(array $value): EnumValueInterface
    {
        return new EnumValue($value['name'], [
            'description'       => $value['description'] ?? null,
            'deprecationReason' => $value['deprecationReason'] ?? null,
        ]);
    }

    /**
     * @return \Traversable|NamedTypeInterface[]
     */
    public function build(): \Traversable
    {
        foreach ($this->preloaded as $builder) {
            /** @var NamedTypeInterface $type */
            $type = $builder->getType();

            yield $type->getName() => $type;
        }
    }
}
