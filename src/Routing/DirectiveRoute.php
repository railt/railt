<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Illuminate\Support\Str;
use Railt\Container\ContainerInterface;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Document;
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
        $this->exportAction($directive->getDocument(), $directive->getPassedArgument('action'));

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
     * @param Document $document
     * @param string $urn
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    private function exportAction(Document $document, string $urn): void
    {
        [$controller, $action] = \tap(\explode('@', $urn), function (array $parts) use ($urn) {
            if (\count($parts) !== 2) {
                $error = 'The action route argument must contain an urn in the format "Class@action", but "%s" given';
                throw new InvalidActionException(\sprintf($error, $urn));
            }
        });

        $instance = $this->container->make($this->loadControllerClass($document, $controller));

        $this->then(\Closure::fromCallable([$instance, $action]));
    }

    /**
     * @param Document $document
     * @param string $controller
     * @return string
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    private function loadControllerClass(Document $document, string $controller): string
    {
        if (\class_exists($controller)) {
            return $controller;
        }

        foreach ($document->getDirectives('use') as $directive) {
            $class = $directive->getPassedArgument('class');
            $alias = $directive->getPassedArgument('as');

            if ($alias === $controller) {
                return $class;
            }

            if (Str::endsWith($class, '\\' . $alias) && \class_exists($class)) {
                return $class;
            }
        }

        $error = 'Class "%s" is not found in the definition of route action argument';
        throw new InvalidActionException(\sprintf($error, $controller));
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
