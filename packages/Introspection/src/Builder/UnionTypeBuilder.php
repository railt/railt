<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use Railt\TypeSystem\Type\UnionType;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\Introspection\Exception\IntrospectionException;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;

/**
 * Class UnionTypeBuilder
 */
class UnionTypeBuilder extends Builder
{
    /**
     * @var string
     */
    private const ERROR_BAD_TYPE = 'Union can not content non-object GraphQL type %s';

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
            $target = $this->get($name);

            if (! $target instanceof ObjectTypeInterface) {
                throw new IntrospectionException(
                    \sprintf(self::ERROR_BAD_TYPE, $name)
                );
            }

            $type->addType($target);
        }
    }
}
