<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Enum;

use Railt\Reflection\Contracts\Common\HasDescription;
use Railt\Reflection\Contracts\Common\HasDirectivesInterface;
use Railt\Reflection\Contracts\EnumTypeInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;

/**
 * Interface ValueInterface
 */
interface ValueInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasDescription
{
    /**
     * @return EnumTypeInterface
     */
    public function getParent(): EnumTypeInterface;
}
