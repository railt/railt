<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Parser;

use Railt\Component\Parser\Ast\Delegate;
use Railt\Component\Parser\Exception\GrammarException;
use Railt\Component\Parser\Rule\Rule;

/**
 * Class Grammar
 */
class Grammar implements GrammarInterface
{
    /**
     * @var array|Rule[]
     */
    private $rules = [];

    /**
     * @var string|int
     */
    private $root;

    /**
     * @var array|string[]
     */
    private $delegates = [];

    /**
     * Grammar constructor.
     *
     * @param array|Rule[] $rules
     * @param array|string[] $delegates
     * @param string|int|null $root
     */
    public function __construct(array $rules = [], $root = null, array $delegates = [])
    {
        $this->addRules(\array_values($rules));
        $this->addDelegates($delegates);
        $this->root = $root;
    }

    /**
     * @param Rule[] $rules
     * @return GrammarInterface|$this
     */
    public function addRules(array $rules): GrammarInterface
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }

        return $this;
    }

    /**
     * @param Rule $rule
     * @return GrammarInterface|$this
     */
    public function addRule(Rule $rule): GrammarInterface
    {
        $this->rules[$rule->getName()] = $rule;

        return $this;
    }

    /**
     * @param array $delegates
     * @return GrammarInterface|$this
     */
    public function addDelegates(array $delegates): GrammarInterface
    {
        foreach ($delegates as $rule => $delegate) {
            $this->addDelegate($rule, $delegate);
        }

        return $this;
    }

    /**
     * @return string
     * @throws GrammarException
     */
    private function resolveRootRule(): string
    {
        foreach ($this->rules as $i => $rule) {
            if (\is_string($rule->getName())) {
                return $rule->getName();
            }
        }

        throw new GrammarException('Unrecognized root rule');
    }

    /**
     * @param string $rule
     * @return string|null
     */
    public function delegate(string $rule): ?string
    {
        return $this->delegates[$rule] ?? null;
    }

    /**
     * @return int|null|string
     * @throws GrammarException
     */
    public function beginAt()
    {
        if ($this->root === null) {
            $this->root = $this->resolveRootRule();
        }

        return $this->root;
    }

    /**
     * @param int|string $rule
     * @return Rule
     */
    public function fetch($rule): Rule
    {
        return $this->rules[$rule];
    }

    /**
     * @param string $rule
     * @param string $delegate
     * @return GrammarInterface|$this
     */
    public function addDelegate(string $rule, string $delegate): GrammarInterface
    {
        $this->delegates[$rule] = $delegate;

        return $this;
    }

    /**
     * @return iterable|string[]|Delegate[]
     */
    public function getDelegates(): iterable
    {
        return $this->delegates;
    }

    /**
     * @return iterable
     */
    public function getRules(): iterable
    {
        return \array_values($this->rules);
    }
}
