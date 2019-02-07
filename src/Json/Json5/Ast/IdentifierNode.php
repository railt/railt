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
 * Class IdentifierNode
 */
class IdentifierNode implements NodeInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * IdentifierNode constructor.
     *
     * @param string $name
     * @param array $children
     */
    public function __construct(string $name, array $children = [])
    {
        $this->value = (string)\reset($children)->getValue();
    }

    /**
     * @return string
     */
    public function reduce(): string
    {
        return $this->value;
    }
}
