<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction;

use Railt\Reflection\Abstraction\Common\HasArgumentsInterface;
use Railt\Reflection\Abstraction\Common\HasDescription;

/**
 * Interface DirectiveTypeInterface
 * @package Railt\Reflection\Abstraction
 */
interface DirectiveTypeInterface extends
    NamedDefinitionInterface,
    HasArgumentsInterface,
    HasDescription
{
    public const TARGET_ENUM = 'ENUM';
    public const TARGET_QUERY = 'QUERY';
    public const TARGET_UNION = 'UNION';
    public const TARGET_FIELD = 'FIELD';
    public const TARGET_SCALAR = 'SCALAR';
    public const TARGET_SCHEMA = 'SCHEMA';
    public const TARGET_OBJECT = 'OBJECT';
    public const TARGET_MUTATION = 'MUTATION';
    public const TARGET_INTERFACE = 'INTERFACE';
    public const TARGET_ENUM_VALUE = 'ENUM_VALUE';
    public const TARGET_INPUT_OBJECT = 'INPUT_OBJECT';
    public const TARGET_SUBSCRIPTION = 'SUBSCRIPTION';
    public const TARGET_FRAGMENT_SPREAD = 'FRAGMENT_SPREAD';
    public const TARGET_INLINE_FRAGMENT = 'INLINE_FRAGMENT';
    public const TARGET_FIELD_DEFINITION = 'FIELD_DEFINITION';
    public const TARGET_FRAGMENT_DEFINITION = 'FRAGMENT_DEFINITION';
    public const TARGET_ARGUMENT_DEFINITION = 'ARGUMENT_DEFINITION';
    public const TARGET_INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';

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
