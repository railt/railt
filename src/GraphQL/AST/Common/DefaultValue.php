<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Common;

use Railt\GraphQL\AST\Node;
use Railt\GraphQL\AST\Value\ValueInterface;

/**
 * Class DefaultValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class DefaultValue extends Node
{
    /**
     * @var ValueInterface
     */
    public $value;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof ValueInterface) {
            $this->value = $value;

            return true;
        }

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }
}
