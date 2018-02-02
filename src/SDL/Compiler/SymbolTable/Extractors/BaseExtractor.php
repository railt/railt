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

/**
 * Class BaseExtractor
 */
abstract class BaseExtractor implements Extractor
{
    /**
     * @return array|string[]
     */
    abstract protected function getNodeNames(): array;

    /**
     * @param string $namespace
     * @param string $name
     * @return string
     */
    protected function fqn(string $namespace, string $name): string
    {
        if ($namespace) {
            return $namespace . self::T_NAMESPACE_SEPARATOR . $name;
        }

        return $name;
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    public function match(NodeInterface $node): bool
    {
        return \in_array($node->getName(), $this->getNodeNames(), true);
    }
}
