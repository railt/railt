<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable\Extractors\Support;

use Railt\Compiler\Ast\LeafInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\SDL\Compiler\SymbolTable\Extractors\Extractor;

/**
 * Trait NameExtractor
 */
trait NameExtractor
{
    /**
     * @param RuleInterface $ast
     * @param int|null $index
     * @return array|string
     */
    protected function fqn(RuleInterface $ast, int $index = null)
    {
        [$offset, $tokens] = $this->typeName($ast);

        $fqn = \implode('/', $tokens);

        if ($index === null) {
            return [$offset, $fqn];
        }

        if ($index === Extractor::I_OFFSET) {
            return $offset;
        }

        return $fqn;
    }

    /**
     * @param RuleInterface $ast
     * @return array [OFFSET, [TOKENS]]
     */
    protected function typeName(RuleInterface $ast): array
    {
        $offset = null;

        /** @var LeafInterface[] $parts */
        $parts = [];

        foreach ($ast->getChildren() as $child) {
            switch (true) {
                case $child->is('#TypeNamespace'):
                    $parts += \iterator_to_array($this->readFromNamespace($child));
                    break;
                case $child->is('#Name'):
                    /** @var LeafInterface $token */
                    $token = $child->getChild(0);

                    $offset = $token->getOffset();
                    $parts[] = $token->getValue();
            }
        }

        return [$offset, $parts];
    }

    /**
     * @param RuleInterface $rule
     * @return \Traversable|string[]
     */
    private function readFromNamespace(RuleInterface $rule): \Traversable
    {
        foreach ($rule->getChildren() as $name) {
            yield $name->getChild(0)->getValue();
        }
    }
}
