<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable\Extractors\Support;

use Railt\Compiler\Ast\RuleInterface;

/**
 * Trait NameExtractor
 */
trait NameExtractor
{
    /**
     * @param RuleInterface $ast
     * @return string
     */
    protected function fqn(RuleInterface $ast): string
    {
        return \implode('/', $this->typeName($ast));
    }

    /**
     * @param RuleInterface $ast
     * @return array
     */
    protected function typeName(RuleInterface $ast): array
    {
        $parts = [];

        foreach ($ast->getChildren() as $child) {
            switch (true) {
                case $child->is('#TypeNamespace'):
                    $parts += \iterator_to_array($this->readFromNamespace($child));
                    break;
                case $child->is('#Name'):
                    $parts[] = $this->readName($child);
            }
        }

        return $parts;
    }

    /**
     * @param RuleInterface $rule
     * @return \Traversable|string[]
     */
    private function readFromNamespace(RuleInterface $rule): \Traversable
    {
        foreach ($rule->getChildren() as $name) {
            yield $this->readName($name);
        }
    }

    /**
     * @param RuleInterface $name
     * @return string
     */
    private function readName(RuleInterface $name): string
    {
        return $name->getChild(0)->getValue();
    }
}
