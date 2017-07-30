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
 * Class Directive
 * @package Serafim\Railgun\Compiler\Reflection
 */
class Directive
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
     * @return DirectiveDefinition
     * @throws \LogicException
     */
    public function getDefinition(): DirectiveDefinition
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return iterable
     * @throws \LogicException
     */
    public function getArguments(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
