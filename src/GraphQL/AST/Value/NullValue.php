<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

/**
 * Class NullValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class NullValue extends Value
{
    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function getRenderableValue(): string
    {
        return 'null';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'null';
    }
}
