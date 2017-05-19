<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

use Serafim\Railgun\Support\InteractWithFields;
use Serafim\Railgun\Contracts\Types\ObjectTypeInterface;

/**
 * Class AbstractObjectType
 * @package Serafim\Railgun\Types
 */
abstract class AbstractObjectType extends AbstractType implements ObjectTypeInterface
{
    use InteractWithFields;

    /**
     * @return iterable
     */
    public function getInterfaces(): iterable
    {
        return [];
    }
}
