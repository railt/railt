<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use Railt\SDL\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\TypeSystem\Directive;

/**
 * @property DirectiveDefinitionNode $ast
 */
class DirectiveBuilder extends TypeBuilder
{
    /**
     * @return DirectiveInterface|DefinitionInterface
     * @throws \RuntimeException
     */
    public function build(): DirectiveInterface
    {
        $directive = new Directive([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
            'repeatable'  => $this->ast->repeatable,
            'locations'   => $this->buildLocations($this->ast->locations),
        ]);

        $this->register($directive);

        if ($this->ast->arguments) {
            $directive->setArguments($this->makeAll($this->ast->arguments));
        }

        return $directive;
    }

    /**
     * @param iterable|IdentifierNode[] $locations
     * @return iterable|string[]
     */
    protected function buildLocations(iterable $locations): iterable
    {
        foreach ($locations as $location) {
            yield $location->value;
        }
    }
}
