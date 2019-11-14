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
use GraphQL\TypeSystem\Directive;
use Railt\SDL\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Builder\Common\ArgumentsBuilderTrait;

/**
 * @property DirectiveDefinitionNode $ast
 */
class DirectiveBuilder extends TypeBuilder
{
    use ArgumentsBuilderTrait;

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
        ]);

        $this->registerDirective($directive);

        return $directive
            ->withLocations($this->buildLocations($this->ast->locations))
            ->withArguments($this->buildArguments($this->ast->arguments))
            ;
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
