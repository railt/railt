<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Ramsey\Collection\Map\AbstractTypedMap;

/**
 * <code>
 *  export type GraphQLFieldMap<TSource, TContext, TArgs = { [key: string]: any }> = {
 *      [key: string]: GraphQLField<TSource, TContext, TArgs>;
 *  };
 * </code>
 *
 * @method Field[] getIterator()
 * @method Field|null offsetGet(string $name)
 * @method Field|null get(string $name, Field|null $default = null)
 */
final class FieldMap extends AbstractTypedMap
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
        return Field::class;
    }
}
