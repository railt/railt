<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Mapper;

use Railt\Adapters\Event;
use Railt\Events\Dispatcher;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class MapperExtension
 */
class MapperExtension extends BaseExtension
{
    /**
     * @param CompilerInterface $compiler
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

        $events->listen(Event::ACTION_RESOLVED . '*', function (string $event, array $payload) use ($serializer) {
            /** @var FieldDefinition $field */
            [$field, $result] = $payload;

            /** @var ObjectDefinition|ScalarDefinition|InterfaceDefinition $type */
            $type = $field->getTypeDefinition();

            foreach ($type->getDirectives('out') as $directive) {
                $action = $directive->getPassedArgument('action');

                $result = $serializer->serialize($field, $action, $result);
            }

            foreach ($type->getDirectives('map') as $directive) {
                $output = $directive->getPassedArgument('out');

                if ($output !== null) {
                    $result = $serializer->serialize($field, $output, $result);
                }
            }

            return $result;
        });
    }
}
