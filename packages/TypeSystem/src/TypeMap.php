<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Type\NamedTypeInterface;
use Ramsey\Collection\Map\AbstractTypedMap;

/**
 * <code>
 *  type TypeMap = {
 *      [key: string]: GraphQLNamedType
 *  };
 * </code>
 *
 * @method NamedTypeInterface[] getIterator()
 * @method NamedTypeInterface|null offsetGet(string $name)
 * @method NamedTypeInterface|null get(string $name, NamedTypeInterface|null $default = null)
 */
final class TypeMap extends AbstractTypedMap
{
    /**
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * @return string
     */
    public function getValueType(): string
    {
        return NamedTypeInterface::class;
    }
}
