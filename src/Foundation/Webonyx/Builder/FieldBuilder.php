<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Exception\BadResponseException;
use Railt\Foundation\Webonyx\Builder\Common\FieldDefinitionResolverTrait;
use Railt\Foundation\Webonyx\Context;
use Railt\Foundation\Webonyx\Input;
use Railt\Http\RequestInterface;
use Railt\SDL\Contracts\Definitions\EnumDefinition;
use Railt\SDL\Contracts\Definitions\ScalarDefinition;
use Railt\SDL\Contracts\Dependent\Argument\HasArguments;
use Railt\SDL\Contracts\Dependent\FieldDefinition as FieldDefinitionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FieldBuilder
 * @property FieldDefinitionInterface $reflection
 */
class FieldBuilder extends Builder
{
    use FieldDefinitionResolverTrait;

    /**
     * @return FieldDefinition
     * @throws \Exception
     */
    public function build(): FieldDefinition
    {
        return FieldDefinition::create(\array_filter([
            'name'              => $this->reflection->getName(),
            'description'       => $this->reflection->getDescription(),
            'deprecationReason' => $this->reflection->getDeprecationReason(),
            'type'              => $this->buildTypeHint($this->reflection),
            'resolve'           => $this->getResolver(),
            'args'              => $this->buildArguments($this->reflection),
        ]));
    }

    /**
     * @return \Closure
     */
    private function getResolver(): \Closure
    {
        return function ($parent, array $args, Context $ctx, ResolveInfo $info) {
            $event = $this->fireResolving($parent, $args, $ctx, $info);

            if ($event->hasResult() && ! $event->isPropagationStopped()) {
                return $this->assertResult($event->getResult());
            }

            return $this->default();
        };
    }

    /**
     * @param mixed $result
     * @return mixed
     * @throws BadResponseException
     */
    private function assertResult($result)
    {
        if (\is_array($result) || \is_scalar($result)) {
            return $result;
        }

        $error = 'Result of %s field should be scalar or array type, but %s given';
        $error = \sprintf($error, $this->reflection, $this->getTypeString($result));

        throw new BadResponseException($error);
    }

    /**
     * @param mixed $result
     * @return string
     */
    private function getTypeString($result): string
    {
        if (\is_object($result)) {
            return \get_class($result);
        }

        return \strtolower(\gettype($result));
    }

    /**
     * @param mixed $parent
     * @param array $args
     * @param Context $ctx
     * @param ResolveInfo $info
     * @return FieldResolve
     */
    private function fireResolving($parent, array $args, Context $ctx, ResolveInfo $info): FieldResolve
    {
        $event = new FieldResolve($ctx->getConnection(), $ctx->getRequest(), $this->reflection);

        $event->withInputResolver($this->getInputResolver($ctx->getRequest(), $args, $info));
        $event->withParentResult($parent);

        return $this->fire($event);
    }

    /**
     * @param RequestInterface $request
     * @param array $args
     * @param ResolveInfo $info
     * @return \Closure
     */
    protected function getInputResolver(RequestInterface $request, array $args, ResolveInfo $info): \Closure
    {
        return function () use ($request, $args, $info) {
            return new Input($request, $info, $this->reflection, $args);
        };
    }

    /**
     * @return array|null
     */
    private function default(): ?array
    {
        return $this->reflection->isNonNull() && ! $this->isScalar() ? [] : null;
    }

    /**
     * @return bool
     */
    private function isScalar(): bool
    {
        $type = $this->reflection->getTypeDefinition();

        return $type instanceof ScalarDefinition || $type instanceof EnumDefinition;
    }

    /**
     * @param HasArguments $arguments
     * @return array
     * @throws \Exception
     */
    private function buildArguments(HasArguments $arguments): array
    {
        $result = [];

        foreach ($arguments->getArguments() as $argument) {
            if ($this->shouldSkip($argument)) {
                continue;
            }

            $item = \array_filter([
                'name'              => $argument->getName(),
                'description'       => $argument->getDescription(),
                'type'              => $this->buildTypeHint($argument),
                'deprecationReason' => $argument->getDeprecationReason(),
            ]);

            if ($argument->hasDefaultValue()) {
                $item['defaultValue'] = $argument->getDefaultValue();
            }

            $result[] = $item;
        }

        return $result;
    }
}
