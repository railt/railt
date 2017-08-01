<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction;

use Serafim\Railgun\Reflection\Abstraction\Common\HasArgumentsInterface;

/**
 * Interface DirectiveTypeInterface
 * @package Serafim\Railgun\Reflection\Abstraction
 */
interface DirectiveTypeInterface extends
    NamedDefinitionInterface,
    HasArgumentsInterface
{
    public const ENUM                   = 'ENUM';
    public const QUERY                  = 'QUERY';
    public const UNION                  = 'UNION';
    public const FIELD                  = 'FIELD';
    public const SCALAR                 = 'SCALAR';
    public const SCHEMA                 = 'SCHEMA';
    public const OBJECT                 = 'OBJECT';
    public const MUTATION               = 'MUTATION';
    public const INTERFACE              = 'INTERFACE';
    public const ENUM_VALUE             = 'ENUM_VALUE';
    public const INPUT_OBJECT           = 'INPUT_OBJECT';
    public const SUBSCRIPTION           = 'SUBSCRIPTION';
    public const FRAGMENT_SPREAD        = 'FRAGMENT_SPREAD';
    public const INLINE_FRAGMENT        = 'INLINE_FRAGMENT';
    public const FIELD_DEFINITION       = 'FIELD_DEFINITION';
    public const FRAGMENT_DEFINITION    = 'FRAGMENT_DEFINITION';
    public const ARGUMENT_DEFINITION    = 'ARGUMENT_DEFINITION';
    public const INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';

    /**
     * @return iterable|string[]
     */
    public function getTargets(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasTarget(string $name): bool;

    /**
     * @param string $name
     * @return null|string
     */
    public function getTarget(string $name): ?string;
}
