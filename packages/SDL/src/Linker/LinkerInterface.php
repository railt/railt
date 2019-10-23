<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker;

use Railt\Parser\Ast\NameNode;
use Railt\Parser\Ast\Node;
use Railt\SDL\Compiler;

/**
 * Interface LinkerInterface
 */
interface LinkerInterface
{
    /**
     * @param Compiler $compiler
     * @param NameNode $name
     * @param Node|null $from
     * @return void
     */
    public function load(Compiler $compiler, NameNode $name, Node $from = null): void;
}
