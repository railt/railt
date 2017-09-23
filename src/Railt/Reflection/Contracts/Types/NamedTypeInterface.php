<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Behavior\Deprecatable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Containers\HasDirectives;

/**
 * Interface NamedTypeInterface
 */
interface NamedTypeInterface extends Nameable, HasDirectives, Deprecatable, TypeInterface
{

}
