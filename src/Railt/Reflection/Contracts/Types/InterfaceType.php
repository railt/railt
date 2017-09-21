<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Containers\HasFields;
use Railt\Reflection\Contracts\Containers\HasDirectives;

/**
 * Interface InterfaceType
 */
interface InterfaceType extends HasDirectives, HasFields, NamedTypeInterface
{

}
