<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Extension;

use Railt\SDL\Ast\Node;
use Phplrt\Parser\Rule\Lexeme;
use Phplrt\Parser\Rule\Optional;
use Phplrt\Parser\Rule\Repetition;
use Phplrt\Parser\Rule\Alternation;
use Phplrt\Visitor\VisitorInterface;
use Phplrt\Parser\Rule\Concatenation;

/**
 * Class Extension
 */
abstract class Extension implements ExtensionInterface
{
    /**
     * @param array $rules
     * @return Concatenation
     */
    protected function sequenceOf(array $rules): Concatenation
    {
        return new Concatenation($rules);
    }

    /**
     * @param string $name
     * @param bool $keep
     * @return Lexeme
     */
    protected function token(string $name, bool $keep = false): Lexeme
    {
        return new Lexeme($name, $keep);
    }

    /**
     * @param int|string $rule
     * @return Optional
     */
    protected function maybe($rule): Optional
    {
        return new Optional($rule);
    }

    /**
     * @param array $rules
     * @return Alternation
     */
    protected function anyOf(array $rules): Alternation
    {
        return new Alternation($rules);
    }

    /**
     * @param int|string $rule
     * @param int $min
     * @param float $max
     * @return Repetition
     */
    protected function repeat($rule, int $min = 0, float $max = \INF): Repetition
    {
        return new Repetition($rule, $min, $max);
    }

    /**
     * @param int|string $rule
     * @param float $max
     * @return Repetition
     */
    protected function many($rule, float $max = \INF): Repetition
    {
        return $this->repeat($rule, 1, $max);
    }

    /**
     * {@inheritDoc}
     */
    public function tokens(): iterable
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): \Generator
    {
        yield from new \EmptyIterator();
    }

    /**
     * {@inheritDoc}
     */
    public function reduce(): iterable
    {
        return [];
    }

    /**
     * @param iterable|Node[] $ast
     * @return array|VisitorInterface[]
     */
    public function visitors(iterable $ast): array
    {
        return [];
    }
}
