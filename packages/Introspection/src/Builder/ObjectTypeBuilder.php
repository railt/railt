<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use Railt\TypeSystem\Type\ObjectType;

/**
 * Class ObjectTypeBuilder
 */
class ObjectTypeBuilder extends StructuredTypeBuilder
{
    /**
     * {@inheritDoc}
     */
    protected static function getKind(): string
    {
        return 'OBJECT';
    }

    /**
     * {@inheritDoc}
     */
    protected function getClass(): string
    {
        return ObjectType::class;
    }
}
