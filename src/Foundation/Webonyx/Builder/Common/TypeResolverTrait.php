<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder\Common;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\Foundation\Event\Resolver\TypeResolve;
use Railt\Foundation\Webonyx\Context;
use Railt\Foundation\Webonyx\Input;

/**
 * Trait TypeResolverTrait
 */
trait TypeResolverTrait
{
    use FieldDefinitionResolverTrait;

    /**
     * @return TypeDefinition
     */
    abstract public function getReflection(): TypeDefinition;

    /**
     * @param string $type
     * @return Type|Directive
     */
    abstract protected function loadType(string $type);

    /**
     * @return \Closure
     */
    protected function getTypeResolver(): \Closure
    {
        return function ($result, Context $ctx, ResolveInfo $info) {
            $field = $this->getFieldDefinition($info);

            $resolving = $this->fireTypeResolving($result, $ctx, $field, $info);

            if ($resolving->isPropagationStopped()) {
                return null;
            }

            if ($resolving->getResult()) {
                return $this->loadType($resolving->getResult());
            }

            throw $this->throwInvalidResolveType($resolving);
        };
    }

    /**
     * @param TypeResolve $resolving
     * @return \LogicException
     */
    private function throwInvalidResolveType(TypeResolve $resolving): \LogicException
    {
        $error = 'Unable to resolve type %s. You must determine one of correct types: %s';
        $types = \implode(', ', $resolving->getInput()->getPreferTypes());

        return new \LogicException(\sprintf($error, $this->getReflection(), $types));
    }

    /**
     * @param mixed $value
     * @param Context $ctx
     * @param FieldDefinition $field
     * @param ResolveInfo $info
     * @return TypeResolve
     */
    private function fireTypeResolving($value, Context $ctx, FieldDefinition $field, ResolveInfo $info): TypeResolve
    {
        $event = new TypeResolve($ctx->getConnection(), $ctx->getRequest(), $field);

        $event->withParentResult($value);
        $event->withInputResolver(function () use ($field, $ctx, $info) {
            return new Input($ctx->getRequest(), $info, $field, []);
        });

        return $this->fire($event);
    }
}
