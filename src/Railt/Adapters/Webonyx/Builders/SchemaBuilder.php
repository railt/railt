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
use Railt\Adapters\Webonyx\Registry;
use Railt\Compiler\Reflection\Dictionary;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
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
        return [
            'query' => $this->load($this->reflection->getQuery()),
        ];
    }

    /**
     * @return array
     */
    private function buildMutation(): array
    {
        if ($this->reflection->hasMutation()) {
            return [
                'mutation' => $this->load($this->reflection->getMutation()),
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
            return [
                'subscription' => $this->load($this->reflection->getSubscription()),
            ];
        }

        return [];
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
