<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Ast;

use Phplrt\Ast\LeafInterface;
use Phplrt\Ast\RuleInterface;

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
                case $child->getName() === 'Sign':
                    $this->positive = $this->isPositiveSign($child);
                    break;

                case $child->getName() === 'ExponentPart':
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
        return $rule->getChild(0)->getName() === 'T_PLUS';
    }

    /**
     * @param RuleInterface $rule
     * @return int
     */
    private function parseExponent(RuleInterface $rule): int
    {
        $exponent = $rule->getChild(0);

        return (int)$exponent->getValue(1);
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
     * @param int $index
     * @return string
     */
    protected function getValue(int $index = 0): string
    {
        return $this->value->getValue($index);
    }
}
