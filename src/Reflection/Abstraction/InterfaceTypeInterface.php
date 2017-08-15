<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection\Abstraction;

use Railgun\Reflection\Abstraction\Common\HasDirectivesInterface;
use Railgun\Reflection\Abstraction\Common\HasFieldsInterface;

/**
 * Interface InterfaceTypeInterface
 * @package Railgun\Reflection\Abstraction
 */
interface InterfaceTypeInterface extends
    NamedDefinitionInterface,
    HasFieldsInterface,
    HasDirectivesInterface
{
}
