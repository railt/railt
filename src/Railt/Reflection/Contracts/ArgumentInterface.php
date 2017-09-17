<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts;

use Railt\Reflection\Contracts\Common\HasDescription;
use Railt\Reflection\Contracts\Common\HasDirectivesInterface;
use Railt\Reflection\Contracts\Type\TypeInterface;

/**
 * Interface ArgumentInterface
 */
interface ArgumentInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasDescription
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
