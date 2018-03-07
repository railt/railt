<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use Railt\Adapters\Webonyx\Registry;
use Railt\Foundation\Events\TypeBuilding;
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition as ReflectionArgument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

/**
 * @property ReflectionArgument $reflection
 */
class ArgumentBuilder extends DependentDefinitionBuilder
{
    /**
     * @param HasArguments $type
     * @param Registry $registry
     * @param Dispatcher $dispatcher
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function buildArguments(HasArguments $type, Registry $registry, Dispatcher $dispatcher): array
    {
        $result = [];

        foreach ($type->getArguments() as $argument) {
            if (TypeBuilding::canBuild($dispatcher, $argument)) {
                $result[$argument->getName()] = (new static($argument, $registry, $dispatcher))->build();
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function build(): array
    {
        $config = [
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'type'        => $this->buildType(),
        ];

        if ($this->reflection->isDeprecated()) {
            $config['deprecationReason'] = $this->reflection->getDeprecationReason();
        }

        if ($this->reflection->hasDefaultValue()) {
            $config['defaultValue'] = $this->reflection->getDefaultValue();
        }

        return $config;
    }
}
