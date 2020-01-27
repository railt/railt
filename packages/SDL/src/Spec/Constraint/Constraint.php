<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec\Constraint;

use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * Class Constraint
 */
abstract class Constraint
{
    /**
     * @var string
     */
    protected const ERROR_NOT_SUPPORTED_MESSAGE =
        '%s not supported by GraphQL %s specification. Please indicate a more ' .
        'modern version of the language specification to enable support for ' .
        'this functionality';

    /**
     * @param NodeInterface $node
     * @param SpecificationInterface $spec
     * @return void
     */
    abstract public static function assert(NodeInterface $node, SpecificationInterface $spec): void;

    /**
     * @param SpecificationInterface $spec
     * @return string
     */
    protected static function notSupported(SpecificationInterface $spec): string
    {
        return \vsprintf(self::ERROR_NOT_SUPPORTED_MESSAGE, [
            static::getName(),
            static::basename(\get_class($spec)),
        ]);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        $class = self::basename(static::class);

        return \ucfirst(\strtolower(\preg_replace('/(.)(?=[A-Z])/u', '$1 ', $class)));
    }

    /**
     * @param string $fqn
     * @return string
     */
    protected static function basename(string $fqn): string
    {
        return \basename(\str_replace('\\', \DIRECTORY_SEPARATOR, $fqn));
    }
}
