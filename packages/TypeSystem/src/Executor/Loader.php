<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem\Executor;

use Phplrt\Visitor\Traverser;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\TypeSystem\Document\DocumentInterface;
use Railt\TypeSystem\Executor\Loader\TypeNamesFilterVisitor;
use Railt\TypeSystem\Executor\Loader\SchemaRegistrarVisitor;
use Railt\TypeSystem\Executor\Loader\TypeSystemRegistrarVisitor;
use Railt\TypeSystem\Executor\Loader\DirectivesRegistrarVisitor;
use Railt\TypeSystem\Executor\Loader\DirectiveDefinitionRegistrarVisitor;

/**
 * Class Loader
 */
class Loader
{
    /**
     * @param iterable $ast
     * @param DocumentInterface $document
     * @param array|null $types
     * @return iterable|NodeInterface|NodeInterface[]
     */
    public function load(iterable $ast, DocumentInterface $document, array $types = null)
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
