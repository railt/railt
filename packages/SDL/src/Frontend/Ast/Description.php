<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast;

use Railt\TypeSystem\Value\StringValue;

/**
 * Class Description
 */
class Description extends Node
{
    /**
     * @var StringValue|null
     */
    public ?StringValue $value = null;

    /**
     * Description constructor.
     *
     * @param StringValue|null $value
     */
    public function __construct(?StringValue $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value->toPHPValue();
    }

    /**
     * @param StringValue|null $description
     * @return static
     */
    public static function create(?StringValue $description): self
    {
        return new static($description);
    }
}
