<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\UnionType;

/**
 * @property-read UnionTypeDefinitionNode $ast
 * @method UnionTypeDefinitionNode getAst()
 */
class UnionTypeDefinitionContext extends NamedTypeContext
{
    /**
     * UnionTypeDefinitionContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param UnionTypeDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, UnionTypeDefinitionNode $ast)
    {
        parent::__construct($context, $schema, $ast);
    }

    /**
     * @param array $variables
     * @return UnionTypeInterface
     * @throws TypeUniquenessException
     * @throws \Throwable
     */
    public function resolve(array $variables = []): UnionTypeInterface
    {
        $ast = $this->precompile($this->ast, $variables);

        $union = new UnionType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        foreach ($ast->types as $member) {
            $union->addType($this->ref($member->type));
        }

        foreach ($ast->directives as $directive) {
            $this->executeDirective($union, $directive);
        }

        return $union;
    }
}
