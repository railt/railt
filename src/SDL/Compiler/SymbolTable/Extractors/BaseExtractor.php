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
use Railt\SDL\Compiler\SymbolTable\Extractors\Support\NameExtractor;

/**
 * Class BaseExtractor
 */
abstract class BaseExtractor implements Extractor
{
    use NameExtractor;

    /**
     * @return array|string[]
     */
    abstract protected function getNodeNames(): array;

    /**
     * @param NodeInterface $node
     * @return bool
     */
    public function match(NodeInterface $node): bool
    {
        return \in_array($node->getName(), $this->getNodeNames(), true);
    }
}
