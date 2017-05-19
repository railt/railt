<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

use Serafim\Railgun\Contracts\Types\TypeInterface;
use Serafim\Railgun\Support\InteractWithName;

/**
 * Class AbstractType
 * @package Serafim\Railgun\Types
 */
abstract class AbstractType implements TypeInterface
{
    use InteractWithName;
}
