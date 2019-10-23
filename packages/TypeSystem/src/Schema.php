<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Type\ObjectType;
use Railt\TypeSystem\Type\NamedTypeInterface;

/**
 * Schema Definition
 *
 * A Schema is created by supplying the root types of each type of operation,
 * query and mutation (optional). A schema definition is then supplied to the
 * validator and executor.
 *
 * <code>
 *  export class GraphQLSchema {
 *      extensions: Maybe<Readonly<Record<string, any>>>;
 *      astNode: Maybe<SchemaDefinitionNode>;
 *      extensionASTNodes: Maybe<ReadonlyArray<SchemaExtensionNode>>;
 *
 *      constructor(config: GraphQLSchemaConfig);
 *      getQueryType(): Maybe<GraphQLObjectType>;
 *      getMutationType(): Maybe<GraphQLObjectType>;
 *      getSubscriptionType(): Maybe<GraphQLObjectType>;
 *      getTypeMap(): TypeMap;
 *      getType(name: string): Maybe<GraphQLNamedType>;
 *
 *      getPossibleTypes(
 *          abstractType: GraphQLAbstractType,
 *      ): ReadonlyArray<GraphQLObjectType>;
 *
 *      getImplementations(
 *          interfaceType: GraphQLInterfaceType,
 *      ): InterfaceImplementations;
 *
 *      isPossibleType(
 *          abstractType: GraphQLAbstractType,
 *          possibleType: GraphQLObjectType,
 *      ): boolean;
 *
 *      isSubType(
 *          abstractType: GraphQLAbstractType,
 *          maybeSubType: GraphQLNamedType,
 *      ): boolean;
 *
 *      getDirectives(): ReadonlyArray<GraphQLDirective>;
 *      getDirective(name: string): Maybe<GraphQLDirective>;
 *
 *      toConfig(): GraphQLSchemaConfig & {
 *          types: GraphQLNamedType[];
 *          directives: GraphQLDirective[];
 *          extensions: Maybe<Readonly<Record<string, any>>>;
 *          extensionASTNodes: ReadonlyArray<SchemaExtensionNode>;
 *          assumeValid: boolean;
 *      };
 * }
 * </code>
 */
class Schema extends Definition
{
    /**
     * @var ObjectType|null
     */
    protected ?ObjectType $query = null;

    /**
     * @var ObjectType|null
     */
    protected ?ObjectType $mutation = null;

    /**
     * @var ObjectType|null
     */
    protected ?ObjectType $subscription = null;

    /**
     * @var TypeMap|NamedTypeInterface[]
     */
    private TypeMap $types;

    /**
     * Schema constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        $this->types ??= new TypeMap();
    }

    public function getQueryType(): ?ObjectType
    {
        return $this->query;
    }

    public function getMutationType(): ?ObjectType
    {
        return $this->mutation;
    }

    public function getSubscriptionType(): ?ObjectType
    {
        return $this->subscription;
    }

    public function getTypeMap(): TypeMap
    {
        return $this->types;
    }

    /**
     * @param string $name
     * @return NamedTypeInterface|null
     */
    public function getType(string $name): ?NamedTypeInterface
    {
        return $this->types->get($name);
    }
}
