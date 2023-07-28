<?php

declare(strict_types=1);

namespace Railt\Router;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Railt\Router\Exception\RouteDefinitionException;
use Railt\Router\Instantiator\InstantiatorInterface;

final class RouteCompiler
{
    private const SERVICE_DELIMITER = '@';
    private const INSTANCE_METHOD_DELIMITER = '->';
    private const STATIC_METHOD_DELIMITER = '::';

    public function __construct(
        private readonly InstantiatorInterface $instantiator,
        private readonly ?ContainerInterface $container = null,
    ) {}

    /**
     * @param non-empty-string $action
     * @param non-empty-string|null $on
     *
     * @return Route
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RouteDefinitionException
     */
    public function compile(string $action, ?string $on = null): Route
    {
        $handler = match (true) {
            \str_contains($action, self::SERVICE_DELIMITER) => $this->parseServiceMethod($action),
            \str_contains($action, self::STATIC_METHOD_DELIMITER) => $this->parseStaticMethod($action),
            \str_contains($action, self::INSTANCE_METHOD_DELIMITER) => $this->parseInstanceMethod($action),
            default => $this->parseFunction($action),
        };

        return new Route($handler, $on);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function parseServiceMethod(string $action): \Closure
    {
        $createInstance = function (string $name) use ($action): mixed {
            if ($this->container === null) {
                throw RouteDefinitionException::fromContainerNotDefined($action);
            }

            return $this->container->get($name);
        };

        return $this->parseMethod($action, self::SERVICE_DELIMITER, $createInstance);
    }

    /**
     * @param non-empty-string $action
     *
     * @psalm-suppress InvalidStringClass
     * @psalm-suppress UnusedVariable
     */
    private function parseStaticMethod(string $action): \Closure
    {
        [$class, $method] = $this->splitAndNormalize($action, self::STATIC_METHOD_DELIMITER);

        try {
            /** @var \Closure */
            return $class::$method(...);
        } catch (\Throwable $e) {
            throw new RouteDefinitionException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    private function parseInstanceMethod(string $action): \Closure
    {
        return $this->parseMethod(
            $action,
            self::INSTANCE_METHOD_DELIMITER,
            $this->instantiator->create(...),
        );
    }

    /**
     * @param non-empty-string $action
     * @param non-empty-string $delimiter
     * @param \Closure(non-empty-string):mixed $createInstance
     */
    private function parseMethod(string $action, string $delimiter, \Closure $createInstance): \Closure
    {
        [$class, $method] = $this->splitAndNormalize($action, $delimiter);

        try {
            $instance = $createInstance($class);
        } catch (\Throwable $e) {
            throw new RouteDefinitionException($e->getMessage(), (int)$e->getCode(), $e);
        }

        if (!\is_object($instance)) {
            throw RouteDefinitionException::fromActionIsNotCallable($action, $instance);
        }

        /** @psalm-suppress MixedMethodCall */
        return match(true) {
            \method_exists($instance, $method)
                => $instance->$method(...),
            \method_exists($instance, '__call')
                => static fn (array $arguments = []): mixed
                    => $instance->$method(...$arguments),
            default => throw RouteDefinitionException::fromActionNotDefined($action, $method),
        };
    }

    /**
     * @param non-empty-string $action
     * @param non-empty-string $delimiter
     * @return array{non-empty-string, non-empty-string}
     */
    private function splitAndNormalize(string $action, string $delimiter): array
    {
        $chunks = \explode($delimiter, $action);

        if (\count($chunks) !== 2) {
            throw RouteDefinitionException::fromInvalidAction($action);
        }

        // Normalize "@service" to "service@__invoke"
        if (\trim($chunks[0]) === '') {
            $chunks = [$chunks[1], '__invoke'];
        }

        // Normalize "service@" to "service@__invoke"
        if (\trim($chunks[1]) === '') {
            $chunks = [$chunks[0], '__invoke'];
        }

        if (\trim($chunks[0]) === '') {
            throw RouteDefinitionException::fromInvalidAction($action);
        }

        /** @var array{non-empty-string, non-empty-string} */
        return $chunks;
    }

    private function parseFunction(string $action): \Closure
    {
        if (\function_exists($action)) {
            return $action(...);
        }

        if (\class_exists($action)) {
            /** @psalm-suppress MixedMethodCall */
            return match(true) {
                \method_exists($action, '__invoke')
                    => $this->parseInstanceMethod($action . '->__invoke'),
                default => throw RouteDefinitionException::fromActionNotDefined($action, '__invoke'),
            };
        }

        throw RouteDefinitionException::fromActionIsNotCallable($action);
    }
}
