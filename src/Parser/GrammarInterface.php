<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Railt\Parser\Ast\Delegate;
use Railt\Parser\Rule\Rule;

/**
 * Interface GrammarInterface
 */
interface GrammarInterface
{
    /**
     * @return string|int
     */
    public function beginAt();

    /**
     * @param string $rule
     * @return string|null
     */
    public function delegate(string $rule): ?string;

    /**
     * @param string|int $rule
     * @return Rule
     */
    public function fetch($rule): Rule;

    /**
     * @return iterable|string[]
     */
    public function getDelegates(): iterable;

    /**
     * @return iterable|Rule[]
     */
    public function getRules(): iterable;

    /**
     * @param Rule $rule
     * @return mixed
     */
    public function addRule(Rule $rule): self;

    /**
     * @param string $key
     * @param string $value
     * @return GrammarInterface
     */
    public function addDelegate(string $key, string $value): self;
}
