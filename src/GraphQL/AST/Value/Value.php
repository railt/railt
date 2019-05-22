<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

use Railt\GraphQL\AST\Node;

/**
 * Class Value
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
abstract class Value extends Node implements ValueInterface
{
    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return string|mixed
     */
    protected function getRenderableValue()
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \sprintf('(%s)%s', $this->getType(), $this->getRenderableValue());
    }
}
