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
use Railt\GraphQL\AST\Value\StringValue;

/**
 * Class Description
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class Description extends Node
{
    /**
     * @var string
     */
    public $value;

    /**
     * @param mixed $value
     * @return bool
     */
    public function each($value): bool
    {
        if ($value instanceof StringValue) {
            $this->value = $value->getValue();
            return true;
        }

        return parent::each($value);
    }
}
