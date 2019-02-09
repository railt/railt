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

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class NaNNode implements NodeInterface
{
    /**
     * @var LeafInterface
     */
    private $value;

    /**
     * BoolNode constructor.
     *
     * @param array $children
     */
    public function __construct(array $children = [])
    {
        $this->value = \reset($children);
    }

    /**
     * @return bool
     */
    private function isNegative(): bool
    {
        return $this->value->getValue(1) === '-';
    }

    /**
     * @return float
     */
    public function reduce(): float
    {
        return $this->isNegative() ? -NAN : NAN;
    }
}
