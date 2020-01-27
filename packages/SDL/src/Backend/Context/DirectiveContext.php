<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\TypeSystem\Directive;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Schema;

/**
 * @property-read DirectiveDefinitionNode $ast
 * @method DirectiveDefinitionNode getAst()
 */
class DirectiveContext extends DefinitionContext
{
    /**
     * DirectiveContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param DirectiveDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, DirectiveDefinitionNode $ast)
    {
        parent::__construct($context, $schema, $ast);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->ast->name->value;
    }

    /**
     * @param array $variables
     * @return DirectiveInterface
     * @throws TypeUniquenessException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function resolve(array $variables = []): DirectiveInterface
    {
        $ast = $this->precompile($this->ast, $variables);

        $directive = new Directive($ast->name->value, [
            'description' => $this->descriptionOf($ast),
            'repeatable'  => $ast->repeatable !== null,
        ]);

        foreach ($ast->arguments as $argument) {
            $directive->addArgument($this->buildArgumentDefinition($argument));
        }

        foreach ($ast->locations as $location) {
            $directive->addLocation($location->name->value);
        }

        return $directive;
    }
}
