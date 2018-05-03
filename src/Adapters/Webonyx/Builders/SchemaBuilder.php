<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;

/**
 * Class SchemaBuilder
 * @property SchemaDefinition $reflection
 */
class SchemaBuilder extends TypeBuilder
{
    /**
     * @return Schema
     * @throws \InvalidArgumentException
     */
    public function build(): Schema
    {
        $schema = \array_merge(
            $this->buildQuery(),
            $this->buildMutation(),
            $this->buildSubscription(),
            $this->buildDirectives(),
            $this->exportTypes()
        );

        return new Schema($schema);
    }

    /**
     * @return array
     */
    private function exportTypes(): array
    {
        $types = [];

        /** @var Dictionary $types */
        $dictionary = $this->make(CompilerInterface::class)->getDictionary();

        // Export objects with interfaces
        $types = $this->with($this->exportInterfacesImplementations($dictionary), $types);

        return [
            'types' => \array_values($types),
        ];
    }

    /**
     * Objects should be exported if GraphQL object field returns an interface.
     *
     * @param \Traversable|Type[] $types
     * @param array $defined
     * @return array
     */
    private function with(\Traversable $types, array $defined): array
    {
        foreach ($types as $type) {
            if (! \array_key_exists($type->name, $defined)) {
                $defined[$type->name] = $type;
            }
        }

        return $defined;
    }

    /**
     * @param Dictionary $dictionary
     * @return \Traversable|Type[]
     */
    private function exportInterfacesImplementations(Dictionary $dictionary): \Traversable
    {
        /** @var ObjectDefinition $object */
        foreach ($dictionary->only(ObjectDefinition::class) as $object) {
            if ($object->getNumberOfInterfaces() > 0) {
                yield $this->load($object);
            }
        }
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function buildQuery(): array
    {
        $query = $this->reflection->getQuery();

        return [
            'query' => $this->load($query),
        ];
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
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
     * @return array
     */
    private function buildDirectives(): array
    {
        /** @var CompilerInterface|Configuration $compiler */
        $compiler = $this->getRegistry()->getContainer()->make(CompilerInterface::class);

        $result = [];

        /** @var DirectiveDefinition $directive */
        foreach ($compiler->getDictionary()->only(DirectiveDefinition::class) as $directive) {
            if ($directive->isAllowedForQueries()) {
                $result[] = $this->load($directive);
            }
        }

        return [
            'directives' => $result,
        ];
    }
}
