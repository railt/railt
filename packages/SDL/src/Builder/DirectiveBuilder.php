<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\SDL\TypeSystem\Directive;
use Railt\SDL\Ast\Name\IdentifierNode;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\DirectiveDefinitionNode;

/**
 * @property-read DirectiveDefinitionNode $ast
 */
class DirectiveBuilder extends TypeBuilder
{
    /**
     * @return DirectiveInterface|DefinitionInterface
     */
    public function build(): DirectiveInterface
    {
        $directive = new Directive();
        $directive->name = $this->ast->name->value;

        $this->registerDirective($directive);

        $directive->description = $this->description($this->ast->description);
        $directive->locations = [...$this->buildLocations($this->ast->locations)];
        $directive->isRepeatable = $this->ast->repeatable;
        $directive->arguments = \iterator_to_array($this->buildArguments($this->ast->arguments));

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
