<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\TypeSystem\Type\UnionType;

/**
 * Class UnionTypeBuilder
 */
class UnionTypeBuilder extends Builder
{
    /**
     * @return string
     */
    protected static function getKind(): string
    {
        return 'UNION';
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return UnionType::class;
    }

    /**
     * @var UnionType $type
     * {@inheritDoc}
     */
    protected function complete(NamedTypeInterface $type, array $data): void
    {
        foreach ($data['possibleTypes'] ?? [] as ['name' => $name]) {
            $type->addType($this->reference($name));
        }
    }
}
