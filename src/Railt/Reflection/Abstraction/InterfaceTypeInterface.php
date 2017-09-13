<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction;

use Railt\Reflection\Abstraction\Common\HasDescription;
use Railt\Reflection\Abstraction\Common\HasDirectivesInterface;
use Railt\Reflection\Abstraction\Common\HasFieldsInterface;

/**
 * Interface InterfaceTypeInterface
 * @package Railt\Reflection\Abstraction
 */
interface InterfaceTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasFieldsInterface,
    HasDescription
{
}
