<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction;

use Railt\Reflection\Abstraction\Common\HasArgumentsInterface;
use Railt\Reflection\Abstraction\Common\HasDirectivesInterface;

/**
 * Interface InputTypeInterface
 * @package Railt\Reflection\Abstraction
 */
interface InputTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasArgumentsInterface
{
}
