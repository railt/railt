<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Adapters\Webonyx\Builder;

use Illuminate\Support\Arr;
use Railgun\Adapters\RequestInterface;
use Railgun\Adapters\Webonyx\Request;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Railgun\Adapters\Webonyx\Builder\Common\HasDescription;

/**
 * Class ObjectTypeBuilder
 * @package Railgun\Adapters\Webonyx\Builder
 * @property-read ObjectTypeInterface $type
 */
class ObjectTypeBuilder extends Builder
{
    use HasDescription;

    /**
     * @return ObjectType
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \LogicException
     * @throws \Railgun\Exceptions\RuntimeException
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
     * @throws \Railgun\Exceptions\IndeterminateBehaviorException
     * @throws \Railgun\Exceptions\CompilerException
     */
    private function fetchData(RequestInterface $request, $value)
    {
        if (is_iterable($value)) {
            $value = to_array($value);
        }

        // When route allowed
        if ($responder = $this->router->resolve($request->getPath())) {
            $route = $this->router->find($request->getPath());
            $this->events->dispatch('route:' . $route->getRoute(), $route);

            if (is_array($value) && array_key_exists($request->getFieldName(), $value)) {
                $value = $value[$request->getFieldName()];
            }

            return $responder->invoke($request, $value);
        }

        // If defined in parent
        if (is_array($value) && array_key_exists($request->getFieldName(), $value)) {
            return $value[$request->getFieldName()];
        }

        return null;
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
