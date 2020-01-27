<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Value;

use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\NullValue;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class VariableValueNode
 */
class VariableValueNode extends Node implements ValueInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * VariableValue constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return '$' . $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @param mixed $value
     * @return ValueInterface
     */
    public static function parse($value): ValueInterface
    {
        return new static((string)$value);
    }

    /**
     * @return mixed
     */
    public function toPHPValue()
    {
        return $this->getName();
    }
}
