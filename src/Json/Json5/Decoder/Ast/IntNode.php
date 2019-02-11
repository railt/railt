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
        return $this->isPositive() ? $this->formatValue() : -$this->formatValue();
    }

    /**
     * @return float|int
     */
    private function formatValue()
    {
        [$exp, $value] = [$this->getExponent(), $this->getValue()];

        switch ($exp <=> 0) {
            case 1:
                return (float)($value . \str_repeat('0', $exp));

            case -1:
                return (float)($value . 'e' . $exp);

            default:
                return (int)$value;
        }
    }
}
