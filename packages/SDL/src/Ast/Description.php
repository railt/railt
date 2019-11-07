<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast;

use Railt\SDL\Ast\Value\StringValueNode;

/**
 * Class Description
 */
class Description extends Node
{
    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $value = null;

    /**
     * Description constructor.
     *
     * @param StringValueNode|null $value
     */
    public function __construct(StringValueNode $value = null)
    {
        $this->value = $value;
    }
}
