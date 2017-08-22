<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction;

use Railt\Reflection\Abstraction\Common\HasDirectivesInterface;
use Railt\Reflection\Abstraction\Type\TypeInterface;

/**
 * Interface ArgumentInterface
 * @package Railt\Reflection\Abstraction\Field
 */
interface ArgumentInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool;

    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @return NamedDefinitionInterface|FieldInterface|InputTypeInterface
     */
    public function getParent(): NamedDefinitionInterface;
}
