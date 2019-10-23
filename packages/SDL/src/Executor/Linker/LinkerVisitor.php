<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Linker;

use Phplrt\Visitor\Visitor;
use Railt\Parser\Ast\Node;
use Railt\Parser\Ast\NameNode;
use Railt\SDL\Compiler;
use Railt\SDL\Executor\Linker\LinkerInterface;
use Railt\SDL\Document\Document;

/**
 * Class LinkerVisitor
 */
abstract class LinkerVisitor extends Visitor
{
    /**
     * @var Document
     */
    protected Document $document;

    /**
     * @var LinkerInterface[]|array
     */
    private array $loaders;

    /**
     * @var Compiler
     */
    private Compiler $compiler;

    /**
     * TypeSystemLoader constructor.
     *
     * @param Document $document
     * @param Compiler $compiler
     * @param array|LinkerInterface[] $loaders
     */
    public function __construct(Document $document, Compiler $compiler, array $loaders = [])
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
