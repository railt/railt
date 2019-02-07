<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Ast;

use Railt\Parser\Ast\LeafInterface;

/**
 * Class HexNode
 */
class HexNode implements NodeInterface
{
    /**
     * @var LeafInterface
     */
    private $leaf;

    /**
     * StringNode constructor.
     *
     * @param string $name
     * @param array $children
     */
    public function __construct(string $name, array $children = [])
    {
        $this->leaf = \reset($children);
    }

    /**
     * @return int
     */
    public function reduce(): int
    {
        $isPositive = $this->leaf->getValue(1) !== '-';

        return $this->parse($this->leaf->getValue(2), $isPositive);
    }

    /**
     * @param string $value
     * @param bool $positive
     * @return int
     */
    private function parse(string $value, bool $positive): int
    {
        $int = \hexdec($value);

        return $positive ? $int : -$int;
    }
}
