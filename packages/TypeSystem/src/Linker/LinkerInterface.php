<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem\Linker;

use Railt\Ast\NameNode;
use Railt\Ast\Node;
use Railt\TypeSystem\CompilerInterface;

/**
 * Interface LinkerInterface
 */
interface LinkerInterface
{
    /**
     * @param CompilerInterface $compiler
     * @param NameNode $name
     * @param Node|null $from
     * @return void
     */
    public function load(CompilerInterface $compiler, NameNode $name, Node $from = null): void;
}
