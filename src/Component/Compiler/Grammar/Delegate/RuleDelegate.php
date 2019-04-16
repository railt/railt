<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler\Grammar\Delegate;

use Railt\Component\Compiler\Grammar\LookaheadIterator;
use Railt\Component\Lexer\Token\EndOfInput;
use Railt\Component\Lexer\Token\Token;
use Railt\Component\Lexer\TokenInterface;
use Railt\Component\Parser\Ast\LeafInterface;
use Railt\Component\Parser\Ast\NodeInterface;
use Railt\Component\Parser\Ast\Rule;
use Railt\Component\Parser\Ast\RuleInterface;

/**
 * Class RuleDelegate
 */
class RuleDelegate extends Rule
{
    /**
     * @return iterable|TokenInterface[]|LookaheadIterator
     */
    public function getInnerTokens(): iterable
    {
        return new LookaheadIterator((function () {
            yield from $this->getTokens($this->first('RuleProduction'));
            yield new EndOfInput(0);
        })->call($this));
    }

    /**
     * @param RuleInterface|NodeInterface $rule
     * @return \Traversable
     */
    private function getTokens(RuleInterface $rule): \Traversable
    {
        /** @var LeafInterface $child */
        foreach ($rule->getChildren() as $child) {
            if ($child instanceof RuleInterface) {
                yield from $this->getTokens($child);
            } else {
                yield new Token($child->getName(), $child->getValues(), $child->getOffset());
            }
        }
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->first('RuleName')->first('T_NAME')->getValue();
    }

    /**
     * @return bool
     */
    public function isKept(): bool
    {
        return (bool)$this->first('ShouldKeep');
    }

    /**
     * @return null|string
     */
    public function getDelegate(): ?string
    {
        $delegate = $this->first('RuleDelegate');

        if ($delegate instanceof RuleInterface) {
            return $delegate->first('T_NAME')->getValue();
        }

        return null;
    }
}
