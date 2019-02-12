<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder\Ast;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
abstract class NumberNode implements NodeInterface
{
    /**
     * @var LeafInterface
     */
    private $value;

    /**
     * @var int
     */
    private $options;

    /**
     * @var bool
     */
    private $positive = true;

    /**
     * @var int
     */
    private $exponent = 0;

    /**
     * BoolNode constructor.
     *
     * @param array|RuleInterface[]|LeafInterface[] $children
     * @param int $options
     */
    public function __construct(array $children = [], int $options = 0)
    {
        $this->options = $options;
        $this->parseValue($children);
    }

    /**
     * @param iterable|RuleInterface[]|LeafInterface[] $children
     */
    private function parseValue(iterable $children): void
    {
        foreach ($children as $child) {
            switch (true) {
                case $child->is('Sign'):
                    $this->positive = $this->isPositiveSign($child);
                    break;

                case $child->is('ExponentPart'):
                    $this->exponent = $this->parseExponent($child);
                    break;

                default:
                    $this->value = $child;
            }
        }
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    private function isPositiveSign(RuleInterface $rule): bool
    {
        return $rule->getChild(0)->is('T_PLUS');
    }

    /**
     * @param RuleInterface $rule
     * @return int
     */
    private function parseExponent(RuleInterface $rule): int
    {
        [$isPositive, $value] = [true, 0];

        foreach ($rule->getChildren() as $child) {
            if ($child->is('Sign')) {
                $isPositive = $this->isPositiveSign($child);
                continue;
            }

            $value = (int)$child->getValue();
        }

        return $isPositive ? $value : -$value;
    }

    /**
     * @return bool
     */
    protected function isPositive(): bool
    {
        return $this->positive;
    }

    /**
     * @return int
     */
    protected function getExponent(): int
    {
        return $this->exponent;
    }

    /**
     * @return bool
     */
    protected function renderAsString(): bool
    {
        return (bool)($this->options & \JSON_BIGINT_AS_STRING);
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function isOverflow(string $value): bool
    {
        switch (true) {
            case $this->isBCMathSupports():
                return $this->isBCMathOverflow($value);

            case $this->isGMPSupports():
                return $this->isGMPOverflow($value);

            default:
                return $value > \PHP_INT_MAX || $value < \PHP_INT_MIN;
        }
    }

    /**
     * @return bool
     */
    private function isBCMathSupports(): bool
    {
        return \function_exists('\\bccomp');
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isBCMathOverflow(string $value): bool
    {
        return \bccomp($value, (string)\PHP_INT_MAX) > 0 || \bccomp($value, (string)\PHP_INT_MIN) < 0;
    }

    /**
     * @return bool
     */
    private function isGMPSupports(): bool
    {
        return
            \function_exists('\\gmp_cmp') &&
            \function_exists('\\gmp_init') &&
            (\strpos('e', $this->getValue()) >= 0 || \strpos('.', $this->getValue()) >= 0);
    }

    /**
     * @param int $index
     * @return string
     */
    protected function getValue(int $index = 0): string
    {
        return $this->value->getValue($index);
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isGMPOverflow(string $value): bool
    {
        return \gmp_cmp(\gmp_init($value), \gmp_init((string)\PHP_INT_MAX)) > 0 ||
            \gmp_cmp(\gmp_init($value), \gmp_init((string)\PHP_INT_MIN)) < 0;
    }
}
