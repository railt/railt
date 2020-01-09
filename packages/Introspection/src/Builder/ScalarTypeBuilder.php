<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use Railt\TypeSystem\Type\ScalarType;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class ScalarTypeBuilder
 */
class ScalarTypeBuilder extends Builder
{
    /**
     * @return string
     */
    protected static function getKind(): string
    {
        return 'SCALAR';
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return ScalarType::class;
    }

    /**
     * @var ScalarType $type
     * {@inheritDoc}
     */
    protected function complete(NamedTypeInterface $type, array $data): void
    {
        // GraphQL Scalar type does not require additional actions
    }
}
