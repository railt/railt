<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Resolvers\Factory;
use Railt\Routing\Resolvers\Resolver;
use Railt\Routing\Store\Box;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FieldResolver
 */
class FieldResolver
{
    private const STRING_METHOD = '__toString';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * FieldResolver constructor.
     * @param RouterInterface $router
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(RouterInterface $router, EventDispatcherInterface $dispatcher)
    {
        $this->router   = $router;
        $this->resolver = new Factory($dispatcher);
    }

    /**
     * @param InputInterface $input
     * @param Box $parent
     * @return array|mixed
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \TypeError
     */
    public function handle(InputInterface $input, ?Box $parent)
    {
        $field = $input->getFieldDefinition();

        foreach ($this->router->get($field) as $route) {
            if (! $route->matchOperation($input->getOperation())) {
                continue;
            }

            return $this->verified($field, $this->resolver->call($input, $route, $parent));
        }

        if ($parent === null && $field->getTypeDefinition() instanceof ObjectDefinition && $field->isNonNull()) {
            return [];
        }

        return $parent[$field->getName()] ?? null;
    }

    /**
     * @param FieldDefinition $field
     * @param mixed $result
     * @return mixed
     * @throws \TypeError
     */
    private function verified(FieldDefinition $field, $result)
    {
        $isScalar = $this->isScalar($field);

        if ($isScalar && ! $this->isValidScalarResponse($result)) {
            throw new \TypeError($this->invalidScalarErrorMessage($field, $result));
        }

        if (! $isScalar && ! $this->isValidCompositeResponse($result)) {
            throw new \TypeError($this->invalidCompositeErrorMessage($field, $result));
        }

        return $result;
    }

    /**
     * @param FieldDefinition $field
     * @return bool
     */
    private function isScalar(FieldDefinition $field): bool
    {
        return $field->getTypeDefinition() instanceof ScalarDefinition;
    }

    /**
     * @param mixed $result
     * @return bool
     */
    private function isValidScalarResponse($result): bool
    {
        $isStringableObject = \is_object($result) && \method_exists($result, self::STRING_METHOD);

        return \is_scalar($result) || $isStringableObject;
    }

    /**
     * @param FieldDefinition $field
     * @param mixed $result
     * @return string
     */
    private function invalidScalarErrorMessage(FieldDefinition $field, $result): string
    {
        $error = 'Result of %s must be a valid scalar or object which is to contain the %s method, but %s given';

        return \sprintf($error, $field, self::STRING_METHOD, $this->typeOf($result));
    }

    /**
     * @param mixed $result
     * @return string
     */
    private function typeOf($result): string
    {
        return \mb_strtolower(\gettype($result));
    }

    /**
     * @param mixed $result
     * @return bool
     */
    private function isValidCompositeResponse($result): bool
    {
        $isArrayAccess = \is_object($result) && $result instanceof \ArrayAccess;

        return \is_array($result) || $isArrayAccess;
    }

    /**
     * @param FieldDefinition $field
     * @param mixed $result
     * @return string
     */
    private function invalidCompositeErrorMessage(FieldDefinition $field, $result): string
    {
        $error = 'Result of %s must be an array or ArrayAccess object, but %s given';

        return \sprintf($error, $field, $this->typeOf($result));
    }
}
