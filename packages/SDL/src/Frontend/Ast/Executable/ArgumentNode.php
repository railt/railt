<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Executable;

use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class ArgumentNode
 *
 * <code>
 *  export interface ArgumentNode {
 *      readonly kind: 'Argument';
 *      readonly loc?: Location;
 *      readonly name: IdentifierNode;
 *      readonly value: ValueNode;
 *  }
 * </code>
 */
class ArgumentNode extends DefinitionNode
{
    /**
     * @var Identifier
     */
    public Identifier $name;

    /**
     * @var ValueInterface
     */
    public ValueInterface $value;

    /**
     * ArgumentNode constructor.
     *
     * @param Identifier $name
     * @param ValueInterface $value
     */
    public function __construct(Identifier $name, ValueInterface $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param array|Node[] $children
     * @return static
     */
    public static function create(array $children): self
    {
        return new static(...$children);
    }
}
