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
class Json5Node implements NodeInterface
{
    /**
     * @var NodeInterface
     */
    private $child;

    /**
     * Json5Node constructor.
     *
     * @param array $children
     */
    public function __construct(array $children = [])
    {
        $this->child = \reset($children);
    }

    /**
     * @return mixed|null
     */
    public function reduce()
    {
        return $this->child instanceof NodeInterface ? $this->child->reduce() : null;
    }
}
