<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

/**
 * Class ObjectType
 * @package Serafim\Railgun\Types
 */
abstract class AbstractObjectType extends AbstractType implements ObjectTypeInterface
{
    /**
     * @return string
     */
    protected function getDescriptionSuffix(): string
    {
        return 'object type definition';
    }

    /**
     * @return iterable|string[]
     */
    public function getInterfaces(): iterable
    {
        return [];
    }
}
