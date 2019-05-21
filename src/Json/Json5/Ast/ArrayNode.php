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
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class ArrayNode implements NodeInterface
{
    /**
     * @var array
     */
    private $children;

    /**
     * ObjectNode constructor.
     *
     * @param array $children
     */
    public function __construct(array $children = [])
    {
        $this->children = $children;
    }

    /**
     * @return array
     */
    public function reduce(): array
    {
        $result = [];

        /** @var NodeInterface $child */
        foreach ($this->children as $child) {
            $result[] = $child->reduce();
        }

        return $result;
    }
}
