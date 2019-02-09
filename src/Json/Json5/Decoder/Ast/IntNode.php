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
     * @return int|string|null
     */
    public function reduce()
    {
        $value = $this->value->getValue(0);

        if ($this->isStringable($value)) {
            return (string)(float)$value;
        }

        return (int)$value;
    }
}
