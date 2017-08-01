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
use Serafim\Railgun\Reflection\Abstraction\Common\HasDirectivesInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;

/**
 * Interface FieldInterface
 * @package Serafim\Railgun\Reflection\Abstraction
 */
interface FieldInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasArgumentsInterface
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * TODO Add parent relation
     * @return NamedDefinitionInterface|InterfaceTypeInterface|ObjectTypeInterface
     */
    //public function getParentType(): NamedDefinitionInterface;
}
