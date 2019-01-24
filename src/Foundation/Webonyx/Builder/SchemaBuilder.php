<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Railt\SDL\Contracts\Definitions\DirectiveDefinition;
use Railt\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;

/**
 * Class SchemaBuilder
 *
 * @property SchemaDefinition $reflection
 */
class SchemaBuilder extends Builder
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array|Directive[]
     */
    private $directives = [];

    /**
     * @return Schema
     */
    public function build(): Schema
    {
        return new Schema(\array_filter([
            'query'        => $this->getQuery(),
            'mutation'     => $this->getMutation(),
            'subscription' => $this->getSubscription(),
            'typeLoader'   => $this->loader,
            'types'        => $this->types,
            'directives'   => $this->directives,
        ]));
    }

    /**
     * @return Type
     */
    private function getQuery(): Type
    {
        return $this->loadType($this->reflection->getQuery()->getName());
    }

    /**
     * @return Type|null
     */
    private function getMutation(): ?Type
    {
        if ($mutation = $this->reflection->getMutation()) {
            return $this->loadType($mutation->getName());
        }

        return null;
    }

    /**
     * @return Type|null
     */
    private function getSubscription(): ?Type
    {
        if ($subscription = $this->reflection->getSubscription()) {
            return $this->loadType($subscription->getName());
        }

        return null;
    }

    /**
     * @param Dictionary $dictionary
     */
    public function preload(Dictionary $dictionary): void
    {
        foreach ($dictionary->all() as $type) {
            if ($type instanceof ObjectDefinition && $type->getNumberOfInterfaces() > 0) {
                $this->types[] = $this->loadType($type->getName());
            }

            if ($type instanceof DirectiveDefinition && $type->isAllowedForQueries()) {
                $this->directives[] = $this->loadType($type->getName());
            }
        }
    }
}
