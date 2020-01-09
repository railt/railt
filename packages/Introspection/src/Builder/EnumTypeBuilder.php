<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use Railt\TypeSystem\Type\EnumType;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class EnumTypeBuilder
 */
class EnumTypeBuilder extends Builder
{
    /**
     * @return string
     */
    protected static function getKind(): string
    {
        return 'ENUM';
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return EnumType::class;
    }

    /**
     * @var EnumType $type
     * {@inheritDoc}
     */
    protected function complete(NamedTypeInterface $type, array $data): void
    {
        foreach ($data['enumValues'] as $value) {
            $type->addValue($this->registry->enumValue($value));
        }
    }
}
