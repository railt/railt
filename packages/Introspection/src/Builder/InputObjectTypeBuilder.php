<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use Railt\TypeSystem\Type\InputObjectType;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class InputObjectTypeBuilder
 */
class InputObjectTypeBuilder extends Builder
{
    /**
     * @return string
     */
    protected static function getKind(): string
    {
        return 'INPUT_OBJECT';
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return InputObjectType::class;
    }

    /**
     * @var InputObjectType $type
     * {@inheritDoc}
     */
    protected function complete(NamedTypeInterface $type, array $data): void
    {
        foreach ($data['inputFields'] as $field) {
            $type->addField($this->registry->inputField($field));
        }
    }
}
