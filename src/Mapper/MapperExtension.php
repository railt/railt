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
use Railt\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Schema\CompilerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

/**
 * Class MapperExtension
 */
class MapperExtension extends BaseExtension
{
    /**
     * @param CompilerInterface $compiler
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     */
    public function boot(CompilerInterface $compiler): void
    {
        $compiler->compile(File::fromPathname(__DIR__ . '/resources/mappings.graphqls'));

        $events     = $this->make(Dispatcher::class);
        $serializer = $this->make(Serializer::class);

        $this->bootArgumentResolver($events, $serializer);
        $this->bootFieldResolver($events, $serializer);
    }

    /**
     * @param Dispatcher $events
     * @param Serializer $serializer
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
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
     * @param Dispatcher $events
     * @param Serializer $serializer
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
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
     * @param FieldDefinition $field
     * @param $result
     * @return mixed
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     */
    private function serialize(Serializer $serializer, FieldDefinition $field, $result)
    {
        /** @var ObjectDefinition|InterfaceDefinition|ScalarDefinition $type */
        $type = $field->getTypeDefinition();

        foreach ($type->getDirectives('out') as $directive) {
            $action = $directive->getPassedArgument('action');

            $result = $serializer->serialize($field, $directive->getDocument(), $action, $result);
        }

        foreach ($type->getDirectives('map') as $directive) {
            $output = $directive->getPassedArgument('out');

            if ($output !== null) {
                $result = $serializer->serialize($field, $directive->getDocument(), $output, $result);
            }
        }

        return $result;
    }

    /**
     * @param Serializer $serializer
     * @param ArgumentDefinition $argument
     * @param $value
     * @return mixed
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     */
    private function unserialize(Serializer $serializer, ArgumentDefinition $argument, $value)
    {
        $type = $argument->getTypeDefinition();

        if ($type instanceof ScalarDefinition) {
            foreach ($type->getDirectives('in') as $directive) {
                $action = $directive->getPassedArgument('action');

                $value = $serializer->unserialize($argument, $directive->getDocument(), $action, $value);
            }

            foreach ($type->getDirectives('map') as $directive) {
                $input = $directive->getPassedArgument('in');

                if ($input !== null) {
                    $value = $serializer->unserialize($argument, $directive->getDocument(), $input, $value);
                }
            }
        }

        foreach ($argument->getDirectives('in') as $directive) {
            $action = $directive->getPassedArgument('action');

            $value = $serializer->unserialize($argument, $directive->getDocument(), $action, $value);
        }

        return $value;
    }
}
