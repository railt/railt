<?php
/**
 * This file is part of compiler package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast;

use Railt\Parser\Environment;
use Railt\Parser\Exception\InternalException;
use Railt\Parser\GrammarInterface;
use Railt\Parser\Trace\Entry;
use Railt\Parser\Trace\Escape;
use Railt\Parser\Trace\Token;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var array
     */
    private $trace;

    /**
     * @var Environment
     */
    private $env;

    /**
     * @var GrammarInterface
     */
    private $grammar;

    /**
     * Builder constructor.
     * @param array $trace
     * @param GrammarInterface $grammar
     * @param Environment $env
     */
    public function __construct(array $trace, GrammarInterface $grammar, Environment $env)
    {
        $this->env = $env;
        $this->trace = $trace;
        $this->grammar = $grammar;
    }

    /**
     * @return RuleInterface
     * @throws InternalException
     */
    public function build(): RuleInterface
    {
        $result = $this->buildTree();

        if (! $result instanceof RuleInterface) {
            throw new InternalException('Cannot build AST, the trace is corrupted');
        }

        return $result;
    }

    /**
     * Build AST from trace.
     * Walk through the trace iteratively and recursively.
     *
     * @param int $i Current trace index.
     * @param array &$children Collected children.
     * @return Node|int
     */
    protected function buildTree(int $i = 0, array &$children = [])
    {
        $max = \count($this->trace);

        while ($i < $max) {
            $trace = $this->trace[$i];

            if ($trace instanceof Entry) {
                $ruleName = $trace->getRule();
                $rule = $this->grammar->fetch($ruleName);
                $isRule = $trace->isTransitional() === false;
                $nextTrace = $this->trace[$i + 1];
                $id = $rule->getNodeId();

                // Optimization: Skip empty trace sequence.
                if ($nextTrace instanceof Escape && $ruleName === $nextTrace->getRule()) {
                    $i += 2;

                    continue;
                }

                if ($isRule === true) {
                    $children[] = $ruleName;
                }

                if ($id !== null) {
                    $children[] = [$id];
                }

                $i = $this->buildTree($i + 1, $children);

                if ($isRule === false) {
                    continue;
                }

                $handle = [];
                $childId = null;

                do {
                    $pop = \array_pop($children);

                    if (\is_object($pop) === true) {
                        $handle[] = $pop;
                    } elseif (\is_array($pop) && $childId === null) {
                        $childId = \reset($pop);
                    } elseif ($ruleName === $pop) {
                        break;
                    }
                } while ($pop !== null);

                if ($childId === null) {
                    $childId = $rule->getDefaultId();
                }

                if ($childId === null) {
                    for ($j = \count($handle) - 1; $j >= 0; --$j) {
                        $children[] = $handle[$j];
                    }

                    continue;
                }

                $children[] = $this->rule((string)($id ?: $childId), \array_reverse($handle), $trace->getOffset());
            } elseif ($trace instanceof Escape) {
                return $i + 1;
            } else {
                if (! $trace->isKept()) {
                    ++$i;
                    continue;
                }

                $children[] = $this->leaf($trace);
                ++$i;
            }
        }

        return $children[0];
    }

    /**
     * @param string $name
     * @param array $children
     * @param int $offset
     * @return RuleInterface
     */
    private function rule(string $name, array $children, int $offset): RuleInterface
    {
        $class = $this->ruleOf($name);

        return new $class($this->env, $name, $children, $offset);
    }

    /**
     * @param string $name
     * @return string|RuleInterface
     */
    protected function ruleOf(string $name): string
    {
        /** @var Rule $class */
        return $this->grammar->delegate($name) ?? Rule::class;
    }

    /**
     * @param Token $token
     * @return LeafInterface
     */
    private function leaf(Token $token): LeafInterface
    {
        $leaf = $this->leafOf($token->getToken()->getName());

        return new $leaf($this->env, $token->getToken());
    }

    /**
     * @param string $token
     * @return string
     */
    protected function leafOf(string $token): string
    {
        return $this->grammar->delegate($token) ?? Leaf::class;
    }
}
