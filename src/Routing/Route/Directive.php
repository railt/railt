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
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\Routing\Route;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Schema\CompilerInterface;

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
        // @route( type: "TypeName" )
        //
        $this->exportTypeDefinition($directive, $this->container->get(CompilerInterface::class)->getDictionary());

        //
        // @[OperationName]( ... )
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
     * @param DirectiveInvocation $directive
     * @param Dictionary $dictionary
     */
    private function exportTypeDefinition(DirectiveInvocation $directive, Dictionary $dictionary): void
    {
        $argument = $directive->getPassedArgument('type');

        if ($argument) {
            $this->wants($dictionary->get($argument));
        }
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
