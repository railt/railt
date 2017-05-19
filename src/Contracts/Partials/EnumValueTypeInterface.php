<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Partials;

use Serafim\Railgun\Contracts\Types\TypeInterface;

/**
 * Interface EnumValueType
 * @package Serafim\Railgun\Contracts\Partials
 */
interface EnumValueTypeInterface extends TypeInterface
{
    /**
     * @return mixed
     */
    public function getValue();
}
