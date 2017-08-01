<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction;

use Serafim\Railgun\Reflection\Abstraction\Common\HasFieldsInterface;
use Serafim\Railgun\Reflection\Abstraction\Common\HasDirectivesInterface;

/**
 * Interface ObjectTypeInterface
 * @package Serafim\Railgun\Reflection\Abstraction
 */
interface ObjectTypeInterface extends
    NamedDefinitionInterface,
    HasFieldsInterface,
    HasDirectivesInterface
{
    /**
     * @return iterable|InterfaceTypeInterface[]
     */
    public function getInterfaces(): iterable;
}
