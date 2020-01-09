<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use Railt\TypeSystem\Type\InterfaceType;

/**
 * Class InterfaceTypeBuilder
 */
class InterfaceTypeBuilder extends StructuredTypeBuilder
{
    /**
     * @return string
     */
    protected static function getKind(): string
    {
        return 'INTERFACE';
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return InterfaceType::class;
    }
}
