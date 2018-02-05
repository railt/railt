<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable\Extractors;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Interface Extractor
 */
interface Extractor
{
    /**
     * @var string
     */
    public const T_NAMESPACE_SEPARATOR = '/';

    /**
     * @param NodeInterface $node
     * @return bool
     */
    public function match(NodeInterface $node): bool;

    /**
     * @param Readable $file
     * @param RuleInterface $node
     * @return \Traversable|Record[]
     */
    public function extract(SymbolTable $table, RuleInterface $node): \Traversable;
}
