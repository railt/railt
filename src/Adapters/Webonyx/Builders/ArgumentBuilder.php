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
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition as ReflectionArgument;

/**
 * @property ReflectionArgument $reflection
 */
class ArgumentBuilder extends DependentDefinitionBuilder
{
    /**
     * @param HasArguments $type
     * @param Registry $registry
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function buildArguments(HasArguments $type, Registry $registry): array
    {
        $result = [];

        foreach ($type->getArguments() as $argument) {
            $result[$argument->getName()] = (new static($argument, $registry))->build();
        }

        return $result;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
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
