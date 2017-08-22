<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builder;

use Illuminate\Support\Arr;
use Railt\Adapters\RequestInterface;
use Railt\Adapters\Webonyx\Request;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Railt\Reflection\Abstraction\ObjectTypeInterface;
use Railt\Adapters\Webonyx\Builder\Common\HasDescription;

/**
 * Class ObjectTypeBuilder
 * @package Railt\Adapters\Webonyx\Builder
 * @property-read ObjectTypeInterface $type
 */
class ObjectTypeBuilder extends Builder
{
    use HasDescription;

    /**
     * @return ObjectType
     * @throws \Railt\Exceptions\IndeterminateBehaviorException
     * @throws \Railt\Exceptions\CompilerException
     * @throws \LogicException
     * @throws \Railt\Exceptions\RuntimeException
     */
    public function build(): ObjectType
    {
        return new ObjectType([
            'name'         => $this->type->getName(),
            'description'  => $this->getDescription(),
            'fields'       => function (): array {
                return iterator_to_array($this->getObjectFields());
            },
            'resolveField' => function ($value, array $args = [], $context, ResolveInfo $info) {
                $request = new Request($args, $info);

                $this->events->dispatch('request:' . $request->getPath(), $request);

                $value = $this->fetchData($request, $value);

                $this->events->dispatch('response:' . $request->getPath(), $value, $request);

                return $value;
            },
        ]);
    }

    /**
     * @param RequestInterface $request
     * @param mixed $value
     * @return mixed
     * @throws \Railt\Exceptions\IndeterminateBehaviorException
     * @throws \Railt\Exceptions\CompilerException
     */
    private function fetchData(RequestInterface $request, $value)
    {
        if (is_iterable($value)) {
            $value = to_array($value);
        }

        // When route allowed
        if ($this->router->has($request->getPath())) {
            return $this->resolveResponder($request, $value);
        }

        // If defined in parent
        if (is_array($value) && array_key_exists($request->getFieldName(), $value)) {
            return $value[$request->getFieldName()];
        }

        return null;
    }

    /**
     * @param RequestInterface $request
     * @param mixed $value
     * @return mixed
     * @throws \Railt\Exceptions\CompilerException
     * @throws \Railt\Exceptions\IndeterminateBehaviorException
     */
    private function resolveResponder(RequestInterface $request, $value)
    {
        if (is_array($value) && array_key_exists($request->getFieldName(), $value)) {
            $value = $value[$request->getFieldName()];
        }

        foreach ($this->router->resolve($request->getPath()) as $responder) {
            $value = $responder->invoke($request, $value);
        }

        return $value;
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
