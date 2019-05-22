<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Common;

use Phplrt\Ast\NodeInterface;
use Railt\GraphQL\AST\Node;

/**
 * Class Name
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class Name extends Node
{
    /**
     * @var string
     */
    public $value;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof NodeInterface) {
            $this->value = $value->getValue();

            return true;
        }

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
