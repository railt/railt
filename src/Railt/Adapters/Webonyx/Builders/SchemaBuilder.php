<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Railt\Adapters\Webonyx\Input;
use Railt\Adapters\Webonyx\Registry;
use Railt\Compiler\Reflection\Dictionary;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class SchemaBuilder
 * @property SchemaDefinition $reflection
 */
class SchemaBuilder extends TypeBuilder
{
    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * SchemaBuilder constructor.
     * @param Dictionary $dictionary
     * @param TypeDefinition $type
     * @param Registry $registry
     */
    public function __construct(Dictionary $dictionary, TypeDefinition $type, Registry $registry)
    {
        $this->dictionary = $dictionary;
        parent::__construct($type, $registry);
    }

    /**
     * @return Schema
     */
    public function build(): Schema
    {
        $schema = \array_merge(
            $this->buildQuery(),
            $this->buildMutation(),
            $this->buildSubscription(),
            $this->buildDirectives()
        );

        return new Schema($schema);
    }

    /**
     * @return array
     */
    private function buildQuery(): array
    {
        $query = $this->reflection->getQuery();

        return [
            'query' => $this->load($query),
        ];
    }

    /**
     * @param ObjectDefinition $on
     * @return \Closure
     */
    private function getResolver(ObjectDefinition $on): \Closure
    {
        return function ($value, array $args = [], $context, ResolveInfo $info) use ($on) {
            $input = new Input($on, $info, $args);

            return [
                'id' => $input->getPath(),
            ];
        };
    }

    /**
     * @return array
     */
    private function buildMutation(): array
    {
        if ($this->reflection->hasMutation()) {
            $mutation = $this->reflection->getMutation();

            return [
                'mutation' => $this->load($mutation),
            ];
        }

        return [];
    }

    /**
     * @return array
     */
    private function buildSubscription(): array
    {
        if ($this->reflection->hasSubscription()) {
            $subscription = $this->reflection->getSubscription();

            return [
                'subscription' => $this->load($subscription),
            ];
        }

        return [];
    }

    /**
     * @param TypeDefinition|ObjectDefinition $type
     * @return Type
     */
    protected function load(TypeDefinition $type): Type
    {
        /** @var ObjectType $result */
        $result = parent::load($type);
        $result->resolveFieldFn = $this->getResolver($type);

        return $result;
    }

    /**
     * @return array
     */
    private function buildDirectives(): array
    {
        $result = [];

        /** @var DirectiveDefinition $directive */
        foreach ($this->dictionary->only(DirectiveDefinition::class) as $directive) {
            if ($directive->isAllowedForQueries()) {
                $result[] = $this->load($directive);
            }
        }

        return [
            'directives' => $result,
        ];
    }
}
