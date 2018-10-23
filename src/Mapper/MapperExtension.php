<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Mapper;

use Railt\Foundation\Events\ActionDispatched;
use Railt\Foundation\Events\ArgumentResolving;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Definitions\UnionDefinition;
use Railt\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\SDL\Contracts\Dependent\DependentDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Contracts\Invocations\Directive\HasDirectives;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\SDL\Schema\CompilerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

/**
 * Class MapperExtension
 */
class MapperExtension extends BaseExtension
{
    /**
     * @param CompilerInterface $compiler
     * @param Dispatcher $events
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
     * @throws \Railt\Kernel\Exceptions\InvalidActionException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function boot(CompilerInterface $compiler, Dispatcher $events): void
    {
        $compiler->compile(File::fromPathname(__DIR__ . '/../../resources/mapper/mappings.graphqls'));

        $serializer = $this->make(Serializer::class);

        $this->bootArgumentResolver($events, $serializer);
        $this->bootFieldResolver($events, $serializer);
    }

    /**
     * @param Dispatcher $events
     * @param Serializer $serializer
     */
    private function bootArgumentResolver(Dispatcher $events, Serializer $serializer): void
    {
        $events->addListener(ArgumentResolving::class, function (ArgumentResolving $event) use ($serializer): void {
            /** @var ArgumentDefinition $argument */
            $argument = $event->getArgument();

            $event->setValue($this->unserialize($serializer, $argument, $event->getValue()));
        });
    }

    /**
     * @param Serializer $serializer
     * @param ArgumentDefinition $argument
     * @param $value
     * @return mixed
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
     * @throws \Railt\Kernel\Exceptions\InvalidActionException
     */
    private function unserialize(Serializer $serializer, ArgumentDefinition $argument, $value)
    {
        /** @var HasDirectives|TypeDefinition $type */
        $type = $argument->getTypeDefinition();

        if ($this->isRootTypeDefinition($type)) {
            foreach ($type->getDirectives('in') as $directive) {
                $action = $directive->getPassedArgument('action');

                $value = $serializer->unserialize($argument, $directive->getDocument(), $action, $value);
            }
        }

        foreach ($argument->getDirectives('in') as $directive) {
            $action = $directive->getPassedArgument('action');

            $value = $serializer->unserialize($argument, $directive->getDocument(), $action, $value);
        }

        return $value;
    }

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    private function isRootTypeDefinition(TypeDefinition $type): bool
    {
        return ! $type instanceof DependentDefinition;
    }

    /**
     * @param Dispatcher $events
     * @param Serializer $serializer
     */
    private function bootFieldResolver(Dispatcher $events, Serializer $serializer): void
    {
        $events->addListener(ActionDispatched::class, function (ActionDispatched $event) use ($serializer): void {
            /** @var FieldDefinition $field */
            $field = $event->getInput()->getFieldDefinition();

            $event->setResponse($this->serialize($serializer, $field, $event->getResponse()));
        });
    }

    /**
     * @param Serializer $serializer
     * @param FieldDefinition $field
     * @param $result
     * @return mixed
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
     * @throws \Railt\Kernel\Exceptions\InvalidActionException
     */
    private function serialize(Serializer $serializer, FieldDefinition $field, $result)
    {
        foreach ($this->actions($field->getTypeDefinition()) as $directive) {
            $type     = $directive->getParent();
            $document = $directive->getDocument();
            $action   = $directive->getPassedArgument('action');

            $result = $serializer->serialize($type, $document, $action, $result);
        }

        return $result;
    }

    /**
     * @param TypeDefinition|HasDirectives $type
     * @return iterable|DirectiveInvocation[]
     */
    private function actions(TypeDefinition $type)
    {
        foreach ($type->getDirectives('out') as $directive) {
            yield $directive;
        }

        if ($type instanceof UnionDefinition) {
            foreach ($type->getTypes() as $provides) {
                yield from $this->actions($provides);
            }
        }

        if ($type instanceof ObjectDefinition) {
            foreach ($type->getInterfaces() as $interface) {
                yield from $this->actions($interface);
            }
        }
    }
}
