<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Behavior\Child;
use Railt\Reflection\Contracts\Containers\HasArguments;

/**
 * Interface FieldType
 */
interface FieldType extends HasArguments, AllowsTypeIndication, Child, NamedTypeDefinition
{
}
