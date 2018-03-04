<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Route;

use Railt\Container\ContainerInterface;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Reflection\Contracts\Invocations\InputInvocation;
use Railt\Routing\Exceptions\InvalidActionException;
use Railt\Routing\Route;
use Railt\Runtime\Contracts\ClassLoader;

/**
 * Class Directive
 */
class Directive extends Route
{
    /**
     * DirectiveRoute constructor.
     * @param ContainerInterface $container
     * @param DirectiveInvocation $directive
     * @param ClassLoader $loader
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    public function __construct(ContainerInterface $container, DirectiveInvocation $directive, ClassLoader $loader)
    {
        /** @var FieldDefinition $field */
        $field = $directive->getParent();

        parent::__construct($container, $field);

        //
        // @route( action: "Controller@action" )
        //
        $this->exportAction($directive->getDocument(), $directive->getPassedArgument('action'), $loader);

        //
        // @route( relation: {parent: "key", child: "key"} )
        //
        $relation = $directive->getPassedArgument('relation');

        if ($relation) {
            $this->exportRelation($relation);
        }

        //
        // @route( operations: [OperationName] )
        //
        $this->exportOperations($directive);
    }

    /**
     * @param Document $document
     * @param string $urn
     * @param ClassLoader $loader
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    private function exportAction(Document $document, string $urn, ClassLoader $loader): void
    {
        [$controller, $action] = \tap(\explode('@', $urn), function (array $parts) use ($urn): void {
            if (\count($parts) !== 2) {
                $error = 'The action route argument must contain an urn in the format "Class@action", but "%s" given';
                throw new InvalidActionException(\sprintf($error, $urn));
            }
        });

        $instance = $this->container->make($loader->load($document, $controller));

        $this->then(\Closure::fromCallable([$instance, $action]));
    }


    /**
     * @param InputInvocation $relation
     */
    private function exportRelation(InputInvocation $relation): void
    {
        $parent = $relation->getPassedArgument('parent');
        $child  = $relation->getPassedArgument('child');

        $this->relation($parent, $child);
    }

    /**
     * @param DirectiveInvocation $directive
     */
    private function exportOperations(DirectiveInvocation $directive): void
    {
        switch ($directive->getName()) {
            case 'query':
                $this->on('query');
                break;
            case 'mutation':
                $this->on('mutation');
                break;
            case 'subscription':
                $this->on('subscription');
                break;
        }
    }
}
