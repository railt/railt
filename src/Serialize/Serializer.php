<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Serialize;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Runtime\Contracts\ClassLoader;

/**
 * Class Serializer
 */
class Serializer
{
    /**
     * @var ClassLoader
     */
    private $loader;

    /**
     * Serializer constructor.
     * @param ClassLoader $loader
     */
    public function __construct(ClassLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param TypeDefinition $type
     * @param DirectiveInvocation $directive
     * @param $result
     * @return iterable
     */
    public function serialize(TypeDefinition $type, DirectiveInvocation $directive, $result): iterable
    {
        [$class, $method] = $this->loader->action(
            $directive->getDocument(),
            $directive->getPassedArgument('action')
        );

        /**
         * Need to implement:
         * - Obtaining an argument from a $method (its type is needed)
         * - Check that the argument of the $method is only one.
         * - If the return type is a list, then you need to check that the result is also an iterator.
         * - If the return type is, then the following steps need to be done for each of the elements:
         * - Verify that the return type matches what is required in the serializer $method.
         * - If not, then return the $result
         * - Otherwise, call the serialization method and return its result.
         */

        return $result;
    }
}
