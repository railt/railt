<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Types;

use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;

/**
 * Interface InterfaceTypeInterface
 * @package Serafim\Railgun\Contracts\Types
 */
interface InterfaceTypeInterface extends TypeInterface
{
    /**
     * @return iterable|FieldTypeInterface[]
     */
    public function getFields(): iterable;
}
