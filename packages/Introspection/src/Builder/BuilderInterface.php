<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use GraphQL\Contracts\TypeSystem\Type\TypeInterface;

/**
 * Interface NamedTypeBuilderInterface
 */
interface BuilderInterface
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @param string $kind
     * @return bool
     */
    public static function match(string $kind): bool;
}
