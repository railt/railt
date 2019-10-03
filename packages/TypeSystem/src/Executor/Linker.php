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
use Railt\TypeSystem\CompilerInterface;
use Railt\TypeSystem\Linker\LinkerInterface;
use Railt\TypeSystem\Document\DocumentInterface;
use Railt\TypeSystem\Executor\Linker\DirectiveExecutionLinkerVisitor;
use Railt\TypeSystem\Executor\Linker\TypeDependenciesLinkerVisitor;

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
     * @var CompilerInterface
     */
    private CompilerInterface $compiler;

    /**
     * Linker constructor.
     *
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler)
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
     * @param DocumentInterface $document
     * @return iterable|NodeInterface|NodeInterface[]
     */
    public function link(iterable $ast, DocumentInterface $document)
    {
        return (new Traverser())
            ->with(new DirectiveExecutionLinkerVisitor($document, $this->compiler, $this->linkers))
            ->with(new TypeDependenciesLinkerVisitor($document, $this->compiler, $this->linkers))
            ->traverse($ast);
    }
}
