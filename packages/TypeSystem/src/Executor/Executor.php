<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem\Executor;

use Railt\Ast\Node;
use Railt\TypeSystem\CompilerInterface;
use Railt\TypeSystem\Linker\LinkerInterface;
use Railt\TypeSystem\Document\DocumentInterface;

/**
 * Class Executor
 */
class Executor
{
    /**
     * @var Loader
     */
    private Loader $loader;

    /**
     * @var Linker
     */
    private Linker $linker;

    /**
     * Executor constructor.
     *
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->loader = new Loader();
        $this->linker = new Linker($compiler);
    }

    /**
     * @param DocumentInterface $document
     * @param iterable|Node[] $ast
     * @return iterable|Node[]
     */
    public function execute(DocumentInterface $document, iterable $ast): iterable
    {
        // 1. Registration of types and expression in the system
        $ast = $this->loader->load($ast, $document);

        // 2. Resolving relations
        $ast = $this->linker->link($ast, $document);

        return $ast;
    }

    /**
     * @param LinkerInterface $linker
     * @return void
     */
    public function addLinker(LinkerInterface $linker): void
    {
        $this->linker->add($linker);
    }
}
