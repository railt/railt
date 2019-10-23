<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Document\Document;
use Railt\SDL\Executor\Loader\DirectiveDefinitionRegistrarVisitor;
use Railt\SDL\Executor\Loader\DirectivesRegistrarVisitor;
use Railt\SDL\Executor\Loader\SchemaRegistrarVisitor;
use Railt\SDL\Executor\Loader\TypeNamesFilterVisitor;
use Railt\SDL\Executor\Loader\TypeSystemRegistrarVisitor;

/**
 * Class Loader
 */
class Loader
{
    /**
     * @param iterable $ast
     * @param Document $document
     * @param array|null $types
     * @return iterable|NodeInterface|NodeInterface[]
     */
    public function load(iterable $ast, Document $document, array $types = null)
    {
        return (new Traverser())
            ->with(new TypeNamesFilterVisitor($types))
            ->with(new SchemaRegistrarVisitor($document))
            ->with(new DirectiveDefinitionRegistrarVisitor($document))
            ->with(new TypeSystemRegistrarVisitor($document))
            ->with(new DirectivesRegistrarVisitor($document))
            ->traverse($ast);
    }
}
