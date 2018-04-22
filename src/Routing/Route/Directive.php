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
use Railt\Foundation\Kernel\Contracts\ClassLoader;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Reflection\Contracts\Invocations\InputInvocation;
use Railt\Routing\Route;

/**
 * Class Directive
 */
class Directive extends Route
{
    /**
     * Directive constructor.
     * @param ContainerInterface $container
     * @param DirectiveInvocation $directive
     * @param ClassLoader $loader
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
     * @param string $action
     * @param ClassLoader $loader
     */
    private function exportAction(Document $document, string $action, ClassLoader $loader): void
    {
        [$controller, $method] = $loader->action($document, $action);

        $instance = $this->container->make($controller);

        $this->then(\Closure::fromCallable([$instance, $method]));
    }

    /**
     * @param InputInvocation $relation
     */
    private function exportRelation(InputInvocation $relation): void
    {
        $parent = $relation->getPassedArgument('parent');
        $child  = $relation->getPassedArgument('child');

        $this->relation($child, $parent);
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
