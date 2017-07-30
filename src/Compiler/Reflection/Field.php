<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection;

/**
 * Class Field
 * @package Serafim\Railgun\Compiler\Reflection
 */
class Field
{
    /**
     * @return string
     * @throws \LogicException
     */
    public function getName(): string
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return Value
     * @throws \LogicException
     */
    public function getValue(): Value
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return bool
     * @throws \LogicException
     */
    public function hasDefaultValue(): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @throws \LogicException
     */
    public function getDefaultValue()
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return iterable|Directive[]
     * @throws \LogicException
     */
    public function getDirectives(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
