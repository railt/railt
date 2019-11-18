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
 * Class NameNode
 */
class NameNode extends Node
{
    /**
     * @var array|IdentifierNode[]
     */
    public array $parts = [];

    /**
     * @var bool
     */
    public bool $isFullyQualified;

    /**
     * Name constructor.
     *
     * @param array|IdentifierNode[] $parts
     * @param bool $fqn
     */
    public function __construct(array $parts, bool $fqn = false)
    {
        $this->parts = $parts;
        $this->isFullyQualified = $fqn;
    }
}
