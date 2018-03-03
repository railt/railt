<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Reflection\Contracts\Invocations\InputInvocation;
use Railt\Routing\Exceptions\InvalidActionException;

/**
 * Class DirectiveRoute
 */
class DirectiveRoute extends Route
{
    /**
     * DirectiveRoute constructor.
     * @param ContainerInterface $container
     * @param TypeDefinition $type
     * @param DirectiveInvocation $directive
     * @throws InvalidActionException
     */
    public function __construct(ContainerInterface $container, TypeDefinition $type, DirectiveInvocation $directive)
    {
        parent::__construct($container, $type);

        //
        // @route( action: "Controller@action" )
        //
        $this->exportAction($directive->getPassedArgument('action'));

        //
        // @route( relation: {parent: "key", child: "key"} )
        //
        $relation = $directive->getPassedArgument('relation');

        if ($relation) {
            $this->exportRelation($relation, $directive->getParent());
        }

        //
        // @route( operations: [OperationName] )
        //
        $operations = $directive->getPassedArgument('operations');

        if ($operations) {
            $this->exportOperations($directive->getPassedArgument('operations'));
        }
    }

    /**
     * @param string $urn
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    private function exportAction(string $urn): void
    {
        $parts = \explode('@', $urn);

        if (\count($parts) !== 2) {
            $error = 'The action route argument must contain an urn in the format "Class@action", but "%s" given';
            throw new InvalidActionException(\sprintf($error, $urn));
        }

        [$controller, $action] = $parts;

        // TODO Add @use directive support

        if (! \class_exists($controller)) {
            $error = 'Class "%s" does not exists defined in route action argument';
            throw new InvalidActionException(\sprintf($error, $controller));
        }

        $instance = $this->container->make($controller);

        $this->then(\Closure::fromCallable([$instance, $action]));
    }

    /**
     * @param InputInvocation $relation
     * @param FieldDefinition|TypeDefinition $field
     */
    private function exportRelation(InputInvocation $relation, FieldDefinition $field): void
    {
        $parent = $relation->getPassedArgument('parent');
        $child  = $relation->getPassedArgument('child');

        $this->relation($parent, $child);
    }

    /**
     * @param array $operations
     */
    private function exportOperations(array $operations): void
    {
        $this->on(...\array_map('\\mb_strtolower', $operations));
    }
}
