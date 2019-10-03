<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem\Executor\Linker;

use Phplrt\Visitor\Visitor;
use Railt\Parser\Node\Node;
use Railt\Parser\Node\NameNode;
use Railt\TypeSystem\CompilerInterface;
use Railt\TypeSystem\Executor\Linker\LinkerInterface;
use Railt\TypeSystem\Document\DocumentInterface;

/**
 * Class LinkerVisitor
 */
abstract class LinkerVisitor extends Visitor
{
    /**
     * @var DocumentInterface
     */
    protected DocumentInterface $document;

    /**
     * @var LinkerInterface[]|array
     */
    private array $loaders;

    /**
     * @var CompilerInterface
     */
    private CompilerInterface $compiler;

    /**
     * TypeSystemLoader constructor.
     *
     * @param DocumentInterface $document
     * @param CompilerInterface $compiler
     * @param array|LinkerInterface[] $loaders
     */
    public function __construct(DocumentInterface $document, CompilerInterface $compiler, array $loaders = [])
    {
        $this->document = $document;
        $this->loaders = $loaders;
        $this->compiler = $compiler;
    }

    /**
     * @param NameNode $name
     * @param Node $from
     * @param \Closure $lookup
     * @return bool
     */
    protected function resolve(NameNode $name, Node $from, \Closure $lookup): bool
    {
        foreach ($this->loaders as $loader) {
            $loader->load($this->compiler, $name, $from);

            if ($lookup($name)) {
                return true;
            }
        }

        return $lookup($name);
    }
}
