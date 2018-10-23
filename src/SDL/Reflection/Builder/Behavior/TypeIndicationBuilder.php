<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Behavior;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exceptions\TypeNotFoundException;

/**
 * Trait TypeIndicationBuilder
 */
trait TypeIndicationBuilder
{
    /**
     * @param NodeInterface $ast
     * @return bool
     * @throws TypeNotFoundException
     */
    protected function compileTypeIndicationBuilder(NodeInterface $ast): bool
    {
        switch ($ast->getName()) {
            case 'Type':
                return $this->buildType($ast);

            case 'List':
                return $this->buildList($ast);
        }

        return false;
    }

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return bool
     * @throws \Railt\SDL\Exceptions\TypeNotFoundException
     */
    private function buildType(NodeInterface $ast): bool
    {
        foreach ($ast->getChildren() as $child) {
            if ($child->is('T_NON_NULL')) {
                if ($this->isList) {
                    $this->isListOfNonNulls = true;
                } else {
                    $this->isNonNull = true;
                }
            } else {
                $this->type = $this->load($child->getValue());
            }
        }

        return true;
    }

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return bool
     * @throws TypeNotFoundException
     */
    private function buildList(NodeInterface $ast): bool
    {
        $this->isList = true;

        foreach ($ast->getChildren() as $child) {
            if ($child->is('Type')) {
                $this->buildType($child);
                continue;
            }

            if ($child->is('T_NON_NULL')) {
                $this->isNonNull = true;
            }
        }

        return true;
    }
}
