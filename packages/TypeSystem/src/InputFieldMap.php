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
use Railt\TypeSystem\Type\NamedTypeInterface;

/**
 * <code>
 *  export type GraphQLInputFieldMap = {
 *      [key: string]: GraphQLInputField
 *  };
 * </code>
 *
 * @method InputField[] getIterator()
 * @method InputField|null offsetGet(string $name)
 * @method InputField|null get(string $name, NamedTypeInterface|null $default = null)
 */
final class InputFieldMap extends AbstractTypedMap
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
        return InputField::class;
    }
}
