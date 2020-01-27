<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Runtime;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * Class Execution
 */
abstract class Execution implements ExecutionInterface
{
    /**
     * @var DefinitionInterface
     */
    private DefinitionInterface $context;

    /**
     * @var iterable
     */
    private iterable $arguments;

    /**
     * Execution constructor.
     *
     * @param DefinitionInterface $context
     * @param iterable|\Traversable|array $arguments
     */
    public function __construct(DefinitionInterface $context, iterable $arguments = [])
    {
        $this->context = $context;

        foreach ($arguments as $name => $value) {
            $this->arguments[$name] = $value;
        }
    }

    /**
     * @return DefinitionInterface
     */
    public function getContext(): DefinitionInterface
    {
        return $this->context;
    }

    /**
     * @return iterable
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getArgument(string $name)
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return isset($this->arguments[$name]) || \array_key_exists($name, $this->arguments);
    }
}
