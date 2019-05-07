<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder\Ast;

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class IntNode extends NumberNode
{
    /**
     * @return int|string|float
     */
    public function reduce()
    {
        return $this->renderValue();
    }

    /**
     * @return string
     */
    private function valueToString(): string
    {
        $value = ($this->isPositive() ? '' : '-') . $this->getValue();

        return $this->getExponent() !== 0 ? $value . 'e' . $this->getExponent() : $value;
    }

    /**
     * @return float|int
     */
    private function renderValue()
    {
        $value = $this->valueToString();

        if ($this->getExponent() === 0) {
            if ($this->isOverflow($value)) {
                return $this->renderAsString() ? $value : (float)$value;
            }

            return (int)$value;
        }

        return $this->isOverflow($value) && $this->renderAsString() ? $value : (float)$value;
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function isOverflow(string $value): bool
    {
        if (\function_exists('\\bccomp')) {
            return \bccomp($value, (string)\PHP_INT_MAX) > 0 || \bccomp($value, (string)\PHP_INT_MIN) < 0;
        }

        // Try to fallback
        return $value > \PHP_INT_MAX || $value < \PHP_INT_MIN;
    }
}
