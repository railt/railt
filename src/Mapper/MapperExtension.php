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
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
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

        $this->bootFieldResolver($this->make(Dispatcher::class));
    }

    /**
     * @param Dispatcher $events
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     * @throws \Railt\Mapper\Exceptions\InvalidSignatureException
     */
    private function bootFieldResolver(Dispatcher $events): void
    {
        $serializer = $this->make(Serializer::class);

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
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     */
    private function serialize(Serializer $serializer, FieldDefinition $field, $result)
    {
        /** @var ObjectDefinition|InterfaceDefinition $type */
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
}
