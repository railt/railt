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

/**
 * Interface EnumTypeInterface
 */
interface EnumTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasDescription
{
    /**
     * @return iterable|ValueInterface[]
     */
    public function getValues(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool;

    /**
     * @param string $name
     * @return null|ValueInterface
     */
    public function getValue(string $name): ?ValueInterface;
}
