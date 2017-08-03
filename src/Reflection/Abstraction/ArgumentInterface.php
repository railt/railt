<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction;

use Serafim\Railgun\Reflection\Abstraction\Common\HasDirectivesInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;

/**
 * Interface ArgumentInterface
 * @package Serafim\Railgun\Reflection\Abstraction\Field
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
