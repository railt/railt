<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Schema;
use Railt\SDL\Reflection\CompilerInterface;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;

/**
 * Class SchemaBuilder
 * @property SchemaDefinition $reflection
 */
class SchemaBuilder extends TypeBuilder
{
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
     * @return array
     */
    private function buildDirectives(): array
    {
        /** @var CompilerInterface $compiler */
        $compiler = $this->getRegistry()->getContainer()->make(CompilerInterface::class);

        $result = [];

        /** @var DirectiveDefinition $directive */
        foreach ($compiler->only(DirectiveDefinition::class) as $directive) {
            if ($directive->isAllowedForQueries()) {
                $result[] = $this->load($directive);
            }
        }

        return [
            'directives' => $result,
        ];
    }
}
