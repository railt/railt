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
use Railt\Reflection\Abstraction\Common\HasDirectivesInterface;
use Railt\Reflection\Abstraction\Type\TypeInterface;

/**
 * Interface FieldInterface
 * @package Railt\Reflection\Abstraction
 */
interface FieldInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasArgumentsInterface,
    HasDescription
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @return NamedDefinitionInterface|InterfaceTypeInterface|ObjectTypeInterface
     */
    public function getParent(): NamedDefinitionInterface;

    /**
     * @return bool
     */
    public function isList(): bool;

    /**
     * @return bool
     */
    public function nonNull(): bool;

    /**
     * @return NamedDefinitionInterface
     */
    public function getRelationDefinition(): NamedDefinitionInterface;

    /**
     * @return string
     */
    public function getRelationTypeName(): string;

    /**
     * @return string
     */
    public function getRelationName(): string;
}
