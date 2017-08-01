<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction;

use Serafim\Railgun\Reflection\Abstraction\Common\HasDirectivesInterface;

/**
 * Interface ScalarTypeInterface
 * @package Serafim\Railgun\Reflection\Abstraction
 */
interface ScalarTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface
{
}
