<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime\Webonyx\Builder;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Runtime\Webonyx\Builder\Common\HasDescription;
use Serafim\Railgun\Runtime\Webonyx\Builder\Type\TypeBuilder;
use Serafim\Railgun\Runtime\Webonyx\Request;

/**
 * Class FieldBuilder
 * @package Serafim\Railgun\Runtime\Webonyx
 * @property-read FieldInterface $type
 */
class FieldBuilder extends Builder
{
    use HasDescription;

    /**
     * @return array
     * @throws \Serafim\Railgun\Exceptions\RuntimeException
     * @throws \LogicException
     */
    public function build(): array
    {
        return [
            'type'        => $this->makeType(),
            'description' => $this->getDescription(),
            'args'        => iterator_to_array($this->getFieldArguments()),
            'resolve'     => function ($value, array $args = [], $context, ResolveInfo $info) {
                $request = new Request($args ?? [], $this->type, $info);

                return array_first($this->events->dispatch($request->getPath(), $request), function ($result) {
                    return $result !== null;
                }, $value);
            },
        ];
    }

    /**
     * @return Type
     * @throws \LogicException
     */
    private function makeType(): Type
    {
        return $this->make($this->type->getType(), TypeBuilder::class);
    }

    /**
     * @return \Traversable
     * @throws \LogicException
     */
    private function getFieldArguments(): \Traversable
    {
        foreach ($this->type->getArguments() as $arg) {
            yield $arg->getName() => $this->make($arg, ArgumentBuilder::class);
        }
    }
}
