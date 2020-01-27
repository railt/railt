<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class DirectiveDefinitionLocations
 */
class DirectiveDefinitionLocationNode extends Node
{
    /**
     * @var Identifier
     */
    public Identifier $name;

    /**
     * DirectiveDefinitionLocationNode constructor.
     *
     * @param Identifier $location
     */
    public function __construct(Identifier $location)
    {
        $this->name = $location;
    }

    /**
     * @param array|Identifier[] $children
     * @return array|static[]
     */
    public static function create(array $children): array
    {
        return \array_map(fn(Identifier $loc): self => new static($loc), $children);
    }
}
