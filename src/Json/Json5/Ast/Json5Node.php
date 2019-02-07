<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Ast;

/**
 * Class Json5Node
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
     * @param string $name
     * @param array $children
     */
    public function __construct(string $name, array $children = [])
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
