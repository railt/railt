<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Name;

use Railt\SDL\Ast\Node;

/**
 * Class IdentifierNode
 */
class IdentifierNode extends Node
{
    /**
     * @var string
     */
    public string $value;

    /**
     * Name constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->value = $name;
    }
}
