<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Webonyx\Builder;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Railgun\Webonyx\Builder\Common\HasDescription;

/**
 * Class ObjectTypeBuilder
 * @package Railgun\Webonyx\Builder
 * @property-read ObjectTypeInterface $type
 */
class ObjectTypeBuilder extends Builder
{
    use HasDescription;

    /**
     * @return ObjectType
     * @throws \LogicException
     * @throws \Railgun\Exceptions\RuntimeException
     */
    public function build(): ObjectType
    {
        return new ObjectType([
            'name'        => $this->type->getName(),
            'description' => $this->getDescription(),
            'fields'      => function (): array {
                return iterator_to_array($this->getObjectFields());
            },
            'resolveFields' => function ($value, array $args = [], $context, ResolveInfo $info) {
                $this->events->dispatch(implode('/', $info->path), $info);
            },
        ]);
    }

    /**
     * @return \Traversable
     * @throws \LogicException
     */
    private function getObjectFields(): \Traversable
    {
        foreach ($this->type->getFields() as $field) {
            yield $field->getName() => $this->make($field, FieldBuilder::class);
        }
    }
}
