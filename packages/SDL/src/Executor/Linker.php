<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor;

use Phplrt\Visitor\Traverser;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Compiler;
use Railt\SDL\Linker\LinkerInterface;
use Railt\SDL\Document\Document;
use Railt\SDL\Executor\Linker\DirectiveExecutionLinkerVisitor;
use Railt\SDL\Executor\Linker\TypeDependenciesLinkerVisitor;

/**
 * Class Linker
 */
class Linker
{
    /**
     * @var array|LinkerInterface[]
     */
    private array $linkers = [];

    /**
     * @var Compiler
     */
    private Compiler $compiler;

    /**
     * Linker constructor.
     *
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param LinkerInterface $linker
     * @return void
     */
    public function add(LinkerInterface $linker): void
    {
        $this->linkers[] = $linker;
    }

    /**
     * @param iterable $ast
     * @param Document $document
     * @return iterable|NodeInterface|NodeInterface[]
     */
    public function link(iterable $ast, Document $document)
    {
        return (new Traverser())
            ->with(new DirectiveExecutionLinkerVisitor($document, $this->compiler, $this->linkers))
            ->with(new TypeDependenciesLinkerVisitor($document, $this->compiler, $this->linkers))
            ->traverse($ast);
    }
}
